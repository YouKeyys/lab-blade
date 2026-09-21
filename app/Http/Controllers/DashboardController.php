<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getOverallData(Request $request)
    {
        try {
            // 1. Ambil Sensors Latest
            $sensors = DB::select("
                SELECT 
                    l.lab_id, l.lab_name,
                    (SELECT TOP 1 d.status FROM devices d WHERE d.lab_id = l.lab_id ORDER BY d.last_seen DESC) as current_status,
                    (SELECT TOP 1 d.last_seen FROM devices d WHERE d.lab_id = l.lab_id) as last_seen, 
                    (SELECT TOP 1 dr.temperature FROM device_readings dr JOIN devices d ON dr.device_id = d.device_id WHERE d.lab_id = l.lab_id ORDER BY dr.timestamp DESC) as current_temp,
                    (SELECT TOP 1 dr.humidity FROM device_readings dr JOIN devices d ON dr.device_id = d.device_id WHERE d.lab_id = l.lab_id ORDER BY dr.timestamp DESC) as current_hum,
                    MAX(dr_all.temperature) as max_temp, MIN(dr_all.temperature) as min_temp,
                    MAX(dr_all.humidity) as max_hum, MIN(dr_all.humidity) as min_hum
                FROM labs l
                LEFT JOIN devices d_all ON l.lab_id = d_all.lab_id
                LEFT JOIN device_readings dr_all ON d_all.device_id = dr_all.device_id
                GROUP BY l.lab_id, l.lab_name
            ");

            // 2. Ambil Alerts
            $alerts = DB::select("
                SELECT a.*, l.lab_name as location 
                FROM alerts a 
                JOIN devices d ON a.device_id = d.device_id 
                JOIN labs l ON d.lab_id = l.lab_id
                WHERE a.status = 'active'
                ORDER BY a.triggered_at DESC
            ");

            // 3. Ambil Devices
            $devices = DB::select("
                SELECT 
                    d.device_id, d.device_name, d.status, d.calStatus
                FROM (
                    SELECT 
                        d.device_id, d.device_name, d.status, d.lab_id,
                        (SELECT TOP 1 status FROM calibration_logs c WHERE c.device_id = d.device_id ORDER BY cal_log_id DESC) as calStatus
                    FROM devices d
                ) d
            ");

            // 4. Ambil History 7 Lab sekaligus dengan parameter rentang waktu
            $range = $request->query('range', '24h');
            $start = $request->query('start');
            $end = $request->query('end');
            
            $timeFilter = "";
            $bucketMin = 10;
            $bindings = [];

            if ($range === '1h') { $timeFilter = "AND dr.timestamp >= DATEADD(hour, -1, GETDATE())"; $bucketMin = 1; }
            else if ($range === '24h') { $timeFilter = "AND dr.timestamp >= DATEADD(hour, -24, GETDATE())"; $bucketMin = 10; }
            else if ($range === '7d') { $timeFilter = "AND dr.timestamp >= DATEADD(day, -7, GETDATE())"; $bucketMin = 60; }
            else if ($range === '30d') { $timeFilter = "AND dr.timestamp >= DATEADD(day, -30, GETDATE())"; $bucketMin = 360; }
            else if ($range === 'custom' && $start && $end) {
                $timeFilter = "AND dr.timestamp BETWEEN :startDate AND :endDate";
                $bindings['startDate'] = $start;
                $bindings['endDate'] = $end;
                $bucketMin = 10;
            }

            $labs = ['MCC Quality Lab', 'Calibration Lab 01', 'Calibration Lab 02', 'Oral Health Care Lab', 'Grooming Lab 01', 'Grooming Lab 02', 'Guardline Lab'];
            $histories = [];

            foreach ($labs as $lab) {
                $labBindings = $bindings;
                $labBindings['labName'] = $lab;
                
                $query = "
                    SELECT TOP 150
                        CAST(AVG(dr.temperature) AS DECIMAL(5,1)) as temperature, 
                        CAST(AVG(dr.humidity) AS DECIMAL(5,1)) as humidity
                    FROM device_readings dr
                    JOIN devices d ON dr.device_id = d.device_id
                    JOIN labs l ON d.lab_id = l.lab_id
                    WHERE l.lab_name = :labName $timeFilter
                    GROUP BY DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0)
                    ORDER BY DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0) ASC
                ";
                $histories[] = DB::select($query, $labBindings);
            }

            // Kembalikan 1 objek besar berisi semua data
            return response()->json([
                'sensors' => $sensors,
                'alerts' => $alerts,
                'devices' => $devices,
                'histories' => $histories
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLabData(Request $request, $labName)
    {
        try {
            // 1. Latest Data Khusus Lab Ini
            $latest = DB::select("
                SELECT 
                    (SELECT TOP 1 d.status FROM devices d WHERE d.lab_id = l.lab_id ORDER BY d.last_seen DESC) as current_status,
                    (SELECT TOP 1 d.last_seen FROM devices d WHERE d.lab_id = l.lab_id) as last_seen, 
                    (SELECT TOP 1 dr.temperature FROM device_readings dr JOIN devices d ON dr.device_id = d.device_id WHERE d.lab_id = l.lab_id ORDER BY dr.timestamp DESC) as current_temp,
                    (SELECT TOP 1 dr.humidity FROM device_readings dr JOIN devices d ON dr.device_id = d.device_id WHERE d.lab_id = l.lab_id ORDER BY dr.timestamp DESC) as current_hum
                FROM labs l WHERE l.lab_name = :labName
            ", ['labName' => $labName]);

            // 2. Data Alert Khusus Lab Ini
            $alerts = DB::select("
                SELECT a.* FROM alerts a 
                JOIN devices d ON a.device_id = d.device_id 
                JOIN labs l ON d.lab_id = l.lab_id
                WHERE a.status = 'active' AND l.lab_name = :labName
                ORDER BY a.triggered_at DESC
            ", ['labName' => $labName]);

            // 3. Status Kalibrasi Khusus Lab Ini
            $devices = DB::select("
                SELECT d.device_id, d.status,
                    (SELECT TOP 1 status FROM calibration_logs c WHERE c.device_id = d.device_id ORDER BY cal_log_id DESC) as calStatus
                FROM devices d JOIN labs l ON d.lab_id = l.lab_id WHERE l.lab_name = :labName
            ", ['labName' => $labName]);

            // 4. Lab Details & Rules
            $labDetail = DB::select("SELECT * FROM labs WHERE lab_name = :labName", ['labName' => $labName]);
            $rules = [];
            if (!empty($labDetail)) {
                $rules = DB::select("
                    SELECT ar.* FROM alert_rules ar JOIN devices d ON ar.device_id = d.device_id WHERE d.lab_id = :labId
                ", ['labId' => $labDetail[0]->lab_id]);
            }

            // 5. History Khusus Lab Ini (Sesuai Filter)
            $range = $request->query('range', '24h');
            $start = $request->query('start');
            $end = $request->query('end');
            $interval = $request->query('interval', 10);
            
            $timeFilter = "";
            $bucketMin = (int)$interval ?: 10;
            $bindings = ['labName' => $labName];

            if ($range === '1h') { $timeFilter = "AND dr.timestamp >= DATEADD(hour, -1, GETDATE())"; $bucketMin = 1; }
            else if ($range === '24h') { $timeFilter = "AND dr.timestamp >= DATEADD(hour, -24, GETDATE())"; $bucketMin = 10; }
            else if ($range === '7d') { $timeFilter = "AND dr.timestamp >= DATEADD(day, -7, GETDATE())"; $bucketMin = 60; }
            else if ($range === '30d') { $timeFilter = "AND dr.timestamp >= DATEADD(day, -30, GETDATE())"; $bucketMin = 360; }
            else if ($range === 'custom' && $start && $end) {
                $timeFilter = "AND dr.timestamp BETWEEN :startDate AND :endDate";
                $bindings['startDate'] = $start;
                $bindings['endDate'] = $end;
            }

           $history = DB::select("
                SELECT 
                    CAST(AVG(dr.temperature) AS DECIMAL(5,1)) as temperature, 
                    CAST(AVG(dr.humidity) AS DECIMAL(5,1)) as humidity,
                    FORMAT(DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0), 'dd MMM HH:mm') as time
                FROM device_readings dr
                JOIN devices d ON dr.device_id = d.device_id
                JOIN labs l ON d.lab_id = l.lab_id
                WHERE l.lab_name = :labName $timeFilter
                GROUP BY DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0)
                ORDER BY DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0) ASC
            ", $bindings);

            // 6. Kembalikan Semuanya Dalam 1 Request
            return response()->json([
                'latest' => !empty($latest) ? $latest[0] : null,
                'alerts' => $alerts,
                'devices' => $devices,
                'labDetail' => !empty($labDetail) ? $labDetail[0] : null,
                'rules' => $rules,
                'history' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}