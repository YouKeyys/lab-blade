<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
{
    // ==========================================
    // READ (GET ALL DEVICES)
    // ==========================================
    public function index()
    {
        try {
            $query = "
                SELECT 
                    d.device_id, d.device_name, d.status, d.first_seen, d.last_seen,
                    d.lab_id, d.modbus_slave_id, 
                    l.lab_name as location,
                    (SELECT TOP 1 status FROM calibration_logs c WHERE c.device_id = d.device_id ORDER BY cal_log_id DESC) as calStatus,
                    (SELECT TOP 1 calibration_date FROM calibration_logs c WHERE c.device_id = d.device_id ORDER BY cal_log_id DESC) as lastCal,
                    (SELECT TOP 1 next_due_date FROM calibration_logs c WHERE c.device_id = d.device_id ORDER BY cal_log_id DESC) as nextCal
                FROM devices d
                LEFT JOIN labs l ON d.lab_id = l.lab_id
                ORDER BY d.created_at DESC
            ";
            
            $result = DB::select($query);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch devices', 'message' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // CREATE (ADD NEW DEVICE)
    // ==========================================
    public function store(Request $request)
    {
        try {
            $role = strtolower(
                $request->input('userRole')
                ?? $request->input('role')
                ?? ''
            );

            if ($role !== 'admin') {
                return response()->json([
                    'message' => 'Only Admin can add devices.'
                ], 403);
            }

            DB::table('devices')->insert([
                'device_id' =>
                    $request->input('device_id')
                    ?? $request->input('deviceId'),

                'device_name' =>
                    $request->input('device_name')
                    ?? $request->input('deviceName'),

                'lab_id' =>
                    $request->input('lab_id')
                    ?? $request->input('labId'),

                'modbus_slave_id' =>
                    $request->input('modbus_slave_id')
                    ?? $request->input('modbusSlaveId')
                    ?? 1,

                'status' => 'offline',
                'first_seen' => DB::raw('GETDATE()'),
                'last_seen' => DB::raw('GETDATE()')
            ]);

            return response()->json([
                'message' => 'Device successfully added!'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to add Device',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // UPDATE DEVICE
    // ==========================================
    public function update(Request $request, $id)
    {
        try {
            $role = strtolower(
                $request->input('userRole')
                ?? $request->input('role')
                ?? ''
            );

            // ==========================================
            // CHECK ROLE
            // ==========================================
            if (!in_array($role, ['admin', 'supervisor'])) {
                return response()->json([
                    'message' => 'Unauthorized.'
                ], 403);
            }

            // ==========================================
            // CHECK DEVICE
            // ==========================================
            $device = DB::table('devices')
                ->where('device_id', $id)
                ->first();

            if (!$device) {
                return response()->json([
                    'message' => 'Device not found.'
                ], 404);
            }

            // ==========================================
            // SUPERVISOR
            // ONLY:
            // Device Name
            // Last Calibration
            // Status
            // ==========================================
            if ($role === 'supervisor') {
                $deviceName = $request->input('device_name')
                    ?? $request->input('deviceName');

                $status = $request->input('status');
                $lastCal = $request->input('lastCal');

                // ------------------------------------------
                // Update Device Name + Status
                // ------------------------------------------
                DB::table('devices')
                    ->where('device_id', $id)
                    ->update([
                        'device_name' => $deviceName,
                        'status' => $status
                    ]);

                // ------------------------------------------
                // Update Last Calibration + Auto Calculate Next Due
                // ------------------------------------------
                if ($lastCal) {
                    // Calculate next due date (1 year from last calibration)
                    $nextDue = date('Y-m-d', strtotime($lastCal . ' +1 year'));

                    $latestCalibration = DB::table('calibration_logs')
                        ->where('device_id', $id)
                        ->orderByDesc('cal_log_id')
                        ->first();

                    if ($latestCalibration) {
                        DB::table('calibration_logs')
                            ->where('cal_log_id', $latestCalibration->cal_log_id)
                            ->update([
                                'calibration_date' => $lastCal,
                                'next_due_date' => $nextDue
                            ]);
                    } else {
                        DB::table('calibration_logs')
                            ->insert([
                                'device_id' => $id,
                                'calibration_date' => $lastCal,
                                'next_due_date' => $nextDue
                            ]);
                    }
                }

                return response()->json([
                    'message' => 'Device updated successfully!'
                ]);
            }

            // ==========================================
            // ADMIN
            // CAN UPDATE ALL DEVICE FIELDS
            // ==========================================
            if ($role === 'admin') {
                DB::table('devices')
                    ->where('device_id', $id)
                    ->update([
                        'device_name' =>
                            $request->input('device_name')
                            ?? $request->input('deviceName'),

                        'lab_id' =>
                            $request->input('lab_id')
                            ?? $request->input('labId'),

                        'modbus_slave_id' =>
                            $request->input('modbus_slave_id')
                            ?? $request->input('modbusSlaveId'),

                        'status' =>
                            $request->input('status')
                    ]);

                // ------------------------------------------
                // Update Last Calibration + Auto Calculate Next Due
                // ------------------------------------------
                $lastCal = $request->input('lastCal');

                if ($lastCal) {
                    // Calculate next due date (1 year from last calibration)
                    $nextDue = date('Y-m-d', strtotime($lastCal . ' +1 year'));

                    $latestCalibration = DB::table('calibration_logs')
                        ->where('device_id', $id)
                        ->orderByDesc('cal_log_id')
                        ->first();

                    if ($latestCalibration) {
                        DB::table('calibration_logs')
                            ->where('cal_log_id', $latestCalibration->cal_log_id)
                            ->update([
                                'calibration_date' => $lastCal,
                                'next_due_date' => $nextDue
                            ]);
                    } else {
                        DB::table('calibration_logs')
                            ->insert([
                                'device_id' => $id,
                                'calibration_date' => $lastCal,
                                'next_due_date' => $nextDue
                            ]);
                    }
                }

                return response()->json([
                    'message' => 'Device updated successfully!'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Device',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // ==========================================
    // GET DEVICE HISTORY LOGS
    // ==========================================
    public function getLogs($id)
    {
        try {
            $query = "
                SELECT log_id as id, created_at as date, event_type as event, description as detail 
                FROM device_logs WHERE device_id = ? ORDER BY created_at DESC
            ";
            $result = DB::select($query, [$id]);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch device history logs', 'error' => $e->getMessage()], 500);
        }
    }
}