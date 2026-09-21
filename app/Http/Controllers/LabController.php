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
            // Hapus lab berdasarkan ID
            DB::table('labs')->where('lab_id', $id)->delete();
            
            return response()->json(['message' => 'Lab berhasil dihapus!']);
            
        } catch (\Exception $e) {
            // Biasa terjadi jika Lab masih dipakai oleh Device (Foreign Key Constraint)
            return response()->json([
                'message' => 'Gagal menghapus Lab. Pastikan tidak ada Device yang terhubung ke Lab ini.', 
                'error' => $e->getMessage()
            ], 500);
        }
    }

}