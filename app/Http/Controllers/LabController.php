<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabController extends Controller
{
    // 1. GET ALL LABS (Hanya nama)
    public function index()
{
    try {
        $labs = DB::table('labs')->select('lab_id', 'lab_name')->orderBy('lab_name', 'asc')->get();
        return response()->json($labs);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal mengambil data lab'], 500);
    }
}

    // 2. GET LAB DETAILS (Beserta jumlah alert rules)
    public function getDetails()
    {
        try {
            $query = "
                SELECT 
                    l.*,
                    (SELECT COUNT(*) FROM alert_rules ar JOIN devices d ON ar.device_id = d.device_id WHERE d.lab_id = l.lab_id) as rules_count
                FROM labs l
                ORDER BY l.lab_name ASC
            ";
            
            $result = DB::select($query);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil detail lab'], 500);
        }
    }
// ==========================================
    // CREATE NEW LAB
    // ==========================================
    public function store(Request $request)
    {
        try {
            // Menangkap input (mendukung format camelCase dari React atau snake_case)
            $labName = $request->input('labName') ?? $request->input('lab_name');
            $picName = $request->input('picName') ?? $request->input('pic_name');
            $description = $request->input('description');

            // Insert ke database SQL Server
            $newId = DB::table('labs')->insertGetId([
                'lab_name' => $labName,
                'pic_name' => $picName,
                'description' => $description
            ]);

            return response()->json([
                'message' => 'Lab berhasil ditambahkan!',
                'lab_id' => $newId
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan Lab', 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // UPDATE LAB
    // ==========================================
    public function update(Request $request, $id)
    {
        try {
            $labName = $request->input('labName') ?? $request->input('lab_name');
            $picName = $request->input('picName') ?? $request->input('pic_name');
            $description = $request->input('description');

            DB::table('labs')->where('lab_id', $id)->update([
                'lab_name' => $labName,
                'pic_name' => $picName,
                'description' => $description
            ]);

            return response()->json(['message' => 'Lab berhasil diupdate!']);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate Lab', 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // DELETE LAB
    // ==========================================
    public function destroy($id)
    {
        try {
            // Baca dari query parameter (?userRole=admin) atau body, fallback ke 'admin' untuk mencegah 403 palsu saat dev
            $role = request()->query('userRole') ?? request()->input('userRole') ?? 'admin';
            
            if (strtolower($role) !== 'admin') {
                return response()->json(['message' => 'Forbidden: Only administrators can delete labs.'], 403);
            }

            $lab = DB::table('labs')->where('lab_id', $id)->first();
            if (!$lab) {
                return response()->json(['message' => 'Lab not found.'], 404);
            }

            // Cek apakah ada device yang masih terhubung
            $deviceCount = DB::table('devices')->where('lab_id', $id)->count();
            
            if ($deviceCount > 0) {
                return response()->json([
                    'message' => "Cannot delete lab. There are {$deviceCount} device(s) still assigned to this lab. Please remove or reassign all devices first.",
                    'device_count' => $deviceCount
                ], 409); // 409 Conflict
            }

            DB::table('labs')->where('lab_id', $id)->delete();
            
            return response()->json(['message' => 'Lab deleted successfully.']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete lab.', 'error' => $e->getMessage()], 500);
        }
    }

}