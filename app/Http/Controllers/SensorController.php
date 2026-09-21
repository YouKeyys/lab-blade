<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Memanggil library Database Laravel

class SensorController extends Controller
{
    public function getLatest()
    {
        try {
            // Sama persis dengan query SQL Server di Node.js kemarin
            $query = "
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
            ";

            // Eksekusi query
            $result = DB::select($query);

            // Return sebagai JSON (Sama seperti res.json() di Express)
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getHistory(Request $request, $labName)
    {
        try {
            $range = $request->query('range', '24h');
            $start = $request->query('start');
            $end = $request->query('end');
            $interval = $request->query('interval', 10);

            $timeFilter = "";
            $topClause = "";
            $bucketMin = 10;
            $bindings = ['labName' => $labName];

            if ($range === '1h') { $timeFilter = "AND dr.timestamp >= DATEADD(hour, -1, GETDATE())"; $bucketMin = 1; }
            else if ($range === '24h') { $timeFilter = "AND dr.timestamp >= DATEADD(hour, -24, GETDATE())"; $bucketMin = 10; }
            else if ($range === '7d') { $timeFilter = "AND dr.timestamp >= DATEADD(day, -7, GETDATE())"; $bucketMin = 60; }
            else if ($range === '30d') { $timeFilter = "AND dr.timestamp >= DATEADD(day, -30, GETDATE())"; $bucketMin = 360; }
            else if ($range === 'custom' && $start && $end) {
                $timeFilter = "AND dr.timestamp BETWEEN :startDate AND :endDate";
                $bindings['startDate'] = $start;
                $bindings['endDate'] = $end;
                $bucketMin = (int)$interval ?: 10;
            } else {
                $topClause = "TOP 150";
            }

            // Memasukkan $bucketMin secara aman ke dalam string query SQL
            $bucketMin = (int)$bucketMin;
            
            $query = "
                SELECT $topClause
                    CAST(AVG(dr.temperature) AS DECIMAL(5,1)) as temperature, 
                    CAST(AVG(dr.humidity) AS DECIMAL(5,1)) as humidity,
                    FORMAT(DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0), 'dd MMM HH:mm') as time,
                    DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0) as sort_time
                FROM device_readings dr
                JOIN devices d ON dr.device_id = d.device_id
                JOIN labs l ON d.lab_id = l.lab_id
                WHERE l.lab_name = :labName $timeFilter
                GROUP BY DATEADD(minute, (DATEDIFF(minute, 0, dr.timestamp) / $bucketMin) * $bucketMin, 0)
                ORDER BY sort_time ASC
            ";
            
            $result = DB::select($query, $bindings);
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}