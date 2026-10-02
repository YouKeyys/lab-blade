<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    public function index()
    {
        try {
            $query = "
                SELECT 
                    a.alert_id as id,
                    a.triggered_at,
                    a.device_id as deviceId,
                    l.lab_name as location,
                    a.parameter,
                    a.triggered_value as value,
                    a.level,
                    a.status,
                    a.rule_id as ruleId,
                    a.last_reminder_sent_at,
                    a.ack_remarks,   -- <--- TAMBAHKAN
                    a.res_remarks,   -- <--- TAMBAHKAN
                    CONCAT(r.operator, ' ', r.threshold_value) as threshold
                FROM alerts a
                JOIN devices d ON a.device_id = d.device_id
                JOIN labs l ON d.lab_id = l.lab_id
                LEFT JOIN alert_rules r ON a.rule_id = r.rule_id
                ORDER BY a.triggered_at DESC
            ";
            
            $result = DB::select($query);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch alerts', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $status = $request->input('status');
            $userId = $request->input('userId');
            $remarks = $request->input('remarks', null); 

            if ($status === 'Acknowledged') {
                DB::table('alerts')->where('alert_id', $id)->update([
                    'status' => $status,
                    'acknowledged_at' => DB::raw('GETDATE()'),
                    'acknowledged_by' => $userId,
                    'ack_remarks' => $remarks // <--- SIMPAN KHUSUS DI ACK_REMARKS
                ]);
                return response()->json(['message' => 'Alert Acknowledged']);
            }

            if ($status === 'Resolved') {
                $alert = DB::table('alerts')->where('alert_id', $id)->first();
                if (!$alert) return response()->json(['message' => 'Alert not found'], 404);

                DB::transaction(function () use ($alert, $id, $userId, $remarks) {
                    DB::table('alert_history')->insert([
                        'alert_id' => $alert->alert_id,
                        'device_id' => $alert->device_id,
                        'rule_id' => $alert->rule_id ?? 0,
                        'parameter' => $alert->parameter,
                        'triggered_value' => $alert->triggered_value,
                        'level' => $alert->level,
                        'start_time' => $alert->triggered_at,
                        'end_time' => DB::raw('GETDATE()'),
                        'ack_by' => $alert->acknowledged_by ?? null,
                        'resolved_by' => $userId,
                        'ack_remarks' => $alert->ack_remarks ?? null, // <--- PINDAHKAN DARI ALERTS
                        'res_remarks' => $remarks ?? null              // <--- SIMPAN REMARKS RESOLVE BARU
                    ]);

                    // Hapus dari tabel aktif
                    DB::table('alerts')->where('alert_id', $id)->delete();
                });

                return response()->json(['message' => 'Alert Resolved & Archived']);
            }

            return response()->json(['message' => 'Invalid status'], 400);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update alert', 'error' => $e->getMessage()], 500);
        }
    }


    public function history()
    {
        try {
            $query = "
                SELECT 
                    h.history_id as id,
                    FORMAT(h.start_time, 'dd MMM yyyy HH:mm') as start,
                    FORMAT(h.end_time, 'dd MMM yyyy HH:mm') as [end],
                    h.device_id as deviceId,
                    l.lab_name as location,
                    h.parameter,
                    h.triggered_value as peak,
                    CONCAT(r.operator, ' ', r.threshold_value) as threshold,
                    h.level,
                    ISNULL(u1.username, 'System') as ackBy,
                    ISNULL(u2.username, 'System') as resBy,
                    DATEDIFF(minute, h.start_time, h.end_time) as durationMins,
                    h.ack_remarks,   -- <--- PASTIKAN INI ADA
                    h.res_remarks    -- <--- PASTIKAN INI ADA
                FROM alert_history h
                JOIN devices d ON h.device_id = d.device_id
                JOIN labs l ON d.lab_id = l.lab_id
                LEFT JOIN alert_rules r ON h.rule_id = r.rule_id
                LEFT JOIN users u1 ON h.ack_by = u1.user_id
                LEFT JOIN users u2 ON h.resolved_by = u2.user_id
                ORDER BY h.end_time DESC
            ";
            $result = DB::select($query);

            $formatted = array_map(function($r) {
                $mins = (int)$r->durationMins;
                $r->duration = $mins > 60 ? floor($mins / 60) . 'h ' . ($mins % 60) . 'm' : $mins . 'm';
                return $r;
            }, $result);

            return response()->json($formatted);
        } catch (\Exception $e) {
            \Log::error('History fetch error: ' . $e->getMessage()); // Cek log Laravel jika masih error
            return response()->json(['message' => 'Failed to fetch history', 'error' => $e->getMessage()], 500);
        }
    }
    // SIMPAN 4 RULES SEKALIGUS (UPSERT)
    public function storeRule(Request $request)
    {
        try {
            $deviceId = $request->input('deviceId');
            $parameter = $request->input('parameter');
            $margin = $parameter === 'temperature' ? 2 : 5;

            $lowCrit = (float) $request->input('lowerLimit');
            $lowWarn = $lowCrit + $margin;
            $upCrit = (float) $request->input('upperLimit');
            $upWarn = $upCrit - $margin;

            // Hapus rule lama
            DB::table('alert_rules')->where('device_id', $deviceId)->where('parameter', $parameter)->delete();

            // Insert 4 rule baru
            $rules = [
                ['device_id' => $deviceId, 'parameter' => $parameter, 'operator' => '<', 'threshold_value' => $lowCrit, 'severity' => 'critical'],
                ['device_id' => $deviceId, 'parameter' => $parameter, 'operator' => '<', 'threshold_value' => $lowWarn, 'severity' => 'warning'],
                ['device_id' => $deviceId, 'parameter' => $parameter, 'operator' => '>', 'threshold_value' => $upWarn, 'severity' => 'warning'],
                ['device_id' => $deviceId, 'parameter' => $parameter, 'operator' => '>', 'threshold_value' => $upCrit, 'severity' => 'critical'],
            ];
            DB::table('alert_rules')->insert($rules);

            return response()->json(['message' => '4 Rules configured successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to configure rules', 'error' => $e->getMessage()], 500);
        }
    }

    // AMBIL RULES BERDASARKAN LAB
    public function getRulesByLab($labId)
    {
        try {
            $query = "
                SELECT r.*, d.device_name 
                FROM alert_rules r JOIN devices d ON r.device_id = d.device_id
                WHERE d.lab_id = ? ORDER BY r.created_at DESC
            ";                             
            $result = DB::select($query, [$labId]);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch rules'], 500);
        }
    }

    // HAPUS RULE
    public function destroyRule($ruleId)
    {
        try {
            DB::table('alert_rules')->where('rule_id', $ruleId)->delete();
            return response()->json(['message' => 'Rule deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete rule'], 500);
        }
    }
}   