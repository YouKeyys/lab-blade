<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ExportController extends Controller
{
    // Fungsi pembantu untuk membuat matriks kalender
    private function getCalendarMatrix($targetMonthIndex, $targetYear)
    {
        // $targetMonthIndex di Node.js mulai dari 0 (Jan). Di PHP mulai dari 1 (Jan).
        $phpMonth = $targetMonthIndex + 1;
        $firstDayOfMonth = Carbon::createFromDate($targetYear, $phpMonth, 1);
        
        // dayOfWeek: 0 = Sunday, 1 = Monday ... 6 = Saturday
        $dayOfWeek = $firstDayOfMonth->dayOfWeek;
        $diffToMonday = $dayOfWeek === 0 ? -6 : 1 - $dayOfWeek;
        
        $gridStartDate = $firstDayOfMonth->copy()->addDays($diffToMonday);
        $matrix = [];

        for ($w = 0; $w < 5; $w++) {
            for ($d = 0; $d < 5; $d++) {
                $currentDate = $gridStartDate->copy()->addDays(($w * 7) + $d);
                $matrix[] = [
                    'weekIndex' => $w,
                    'dayIndex' => $d,
                    'date' => $currentDate,
                    'isCurrentMonth' => $currentDate->month === $phpMonth,
                    'localDateString' => $currentDate->format('Y-m-d')
                ];
            }
        }
        return $matrix;
    }

    public function downloadForm(Request $request)
    {

    // 1. Tingkatkan batas memori dan waktu eksekusi sementara
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        try {
            $labName = $request->query('labName', 'OHC Lab');
            
            $startDateParam = Carbon::parse($request->query('startDate'));
            $endDateParam = Carbon::parse($request->query('endDate'))->endOfDay();

            $monthsToProcess = [];
            $currentMonthIter = $startDateParam->copy()->startOfMonth();

            while ($currentMonthIter <= $endDateParam) {
                $monthsToProcess[] = $currentMonthIter->copy();
                $currentMonthIter->addMonth();
            }

            $labResult = DB::table('labs')->where('lab_name', $labName)->first();
            $picName = $labResult && $labResult->pic_name ? $labResult->pic_name : "Gading Adibyafhati";

            // Tarik data mentah
            $sensorData = DB::table('device_readings as dr')
                ->join('devices as d', 'dr.device_id', '=', 'd.device_id')
                ->join('labs as l', 'd.lab_id', '=', 'l.lab_id')
                ->where('l.lab_name', $labName)
                ->whereBetween('dr.timestamp', [$startDateParam, $endDateParam])
                ->select('dr.timestamp', 'dr.temperature', 'dr.humidity')
                ->get();

            // [LOGIKA BARU SUPER CEPAT] Kelompokkan data berdasarkan tanggal sejak awal
            $groupedByDate = [];
            foreach ($sensorData as $d) {
                $dateStr = Carbon::parse($d->timestamp)->format('Y-m-d');
                $groupedByDate[$dateStr][] = $d;
            }

            $templatePath = storage_path('app/templates/Form_Template.xlsx');
            $spreadsheet = IOFactory::load($templatePath);

            $monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $sheetNameMap = [
                "May" => "May", "June" => "Jun", "July" => "Jul", "August" => "Aug",
                "September" => "Sep", "October" => "Oct", "November" => "Nov", "December" => "Des"
            ];

            $tempLimitMin = 15; $tempLimitMax = 25;
            $humLimitMin = 25; $humLimitMax = 75;

            foreach ($monthsToProcess as $targetDate) {
                $targetMonthIndex = $targetDate->month - 1; 
                $targetYear = $targetDate->year;
                $targetMonthName = $monthNames[$targetMonthIndex];
                
                if (!isset($sheetNameMap[$targetMonthName])) continue;
                $exactSheetName = $sheetNameMap[$targetMonthName];

                $worksheet = $spreadsheet->getSheetByName($exactSheetName);
                if (!$worksheet) continue;

                $worksheet->setCellValue('D4', ": {$targetMonthName}");
                $worksheet->setCellValue('D5', ": {$targetYear}");
                $worksheet->setCellValue('D6', ": {$labName}");
                $worksheet->setCellValue('W4', ": {$picName}");
                $worksheet->setCellValue('W5', ": Belman Banjarnahor");

                $calendarMatrix = $this->getCalendarMatrix($targetMonthIndex, $targetYear);
                $startCol = 4; 

                foreach ($calendarMatrix as $cell) {
                    if (!$cell['isCurrentMonth']) continue;

                    $dateKey = $cell['localDateString'];
                    
                    // Langsung ambil data hari itu (jika ada), tanpa perlu nge-filter seluruh 80.000 data
                    $todaysData = isset($groupedByDate[$dateKey]) ? collect($groupedByDate[$dateKey]) : collect([]);

                    if ($todaysData->isEmpty()) continue;

                    $timeBuckets = [
                        ['index' => 0, 'startHour' => 0, 'endHour' => 8],
                        ['index' => 1, 'startHour' => 8, 'endHour' => 10],
                        ['index' => 2, 'startHour' => 10, 'endHour' => 13],
                        ['index' => 3, 'startHour' => 13, 'endHour' => 15],
                        ['index' => 4, 'startHour' => 15, 'endHour' => 17]
                    ];

                    $rowTemp = 14 + ($cell['weekIndex'] * 7);
                    $rowHum = 15 + ($cell['weekIndex'] * 7);
                    $rowJudge = 16 + ($cell['weekIndex'] * 7);
                    $baseDayColIndex = $startCol + ($cell['dayIndex'] * 5); 

                    foreach ($timeBuckets as $bucket) {
                        $targetColIndex = $baseDayColIndex + $bucket['index'];
                        
                        $bucketData = $todaysData->filter(function($d) use ($bucket) {
                            $ts = Carbon::parse($d->timestamp);
                            $exactTime = $ts->hour + ($ts->minute / 60);
                            return $exactTime > $bucket['startHour'] && $exactTime <= $bucket['endHour'];
                        });

                        if ($bucketData->count() > 0) {
                            $avgTemp = $bucketData->avg('temperature');
                            $avgHum = $bucketData->avg('humidity');
                            
                            $isTempOK = $avgTemp >= $tempLimitMin && $avgTemp <= $tempLimitMax;
                            $isHumOK = $avgHum >= $humLimitMin && $avgHum <= $humLimitMax;
                            $judgement = ($isTempOK && $isHumOK) ? "OK" : "X";

                            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($targetColIndex);

                            $worksheet->setCellValue($colLetter . $rowTemp, round($avgTemp, 1));
                            $worksheet->setCellValue($colLetter . $rowHum, round($avgHum, 1));
                            $worksheet->setCellValue($colLetter . $rowJudge, $judgement);
                        }
                    }
                }
            }

            $safeLabName = str_replace(' ', '_', $labName);
            $shortMonths = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            $startMonthName = $shortMonths[$startDateParam->month - 1];
            $endMonthName = $shortMonths[$endDateParam->month - 1];
            
            $fileSuffix = $startMonthName === $endMonthName 
                ? "{$startMonthName}_{$endDateParam->year}" 
                : "{$startMonthName}-{$endMonthName}_{$endDateParam->year}";
            
            $fileName = "Control_Form_{$safeLabName}_{$fileSuffix}.xlsx";

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'. $fileName .'"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;

        // 2. Ubah \Exception menjadi \Throwable agar Fatal Error tertangkap!
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Gagal meng-generate Excel: ' . $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}