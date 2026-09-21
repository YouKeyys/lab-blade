<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ==========================================
    // 1. SETUP DUMMY ACCOUNTS
    // ==========================================
    public function setup()
    {
        try {
            $hashedAdmin = Hash::make('admin123');
            $hashedSpv = Hash::make('spv123');

            // Insert Admin
            $adminExists = DB::table('users')->where('email', 'admin@lab.com')->exists();
            if (!$adminExists) {
                DB::table('users')->insert([
                    'username' => 'Main Admin',
                    'email' => 'admin@lab.com',
                    'password' => $hashedAdmin,
                    'role' => 'admin'
                ]);
            }

            // Insert Supervisor
            $spv = DB::table('users')->where('email', 'yuki@lab.com')->first();
            if (!$spv) {
                // insertGetId otomatis mengambil ID yang baru masuk (seperti OUTPUT inserted.user_id)
                $spvId = DB::table('users')->insertGetId([
                    'username' => 'Yuki Supervisor',
                    'email' => 'yuki@lab.com',
                    'password' => $hashedSpv,
                    'role' => 'supervisor'
                ]);

                // Assign ke Lab 1 & 2
                $labs = [1, 2];
                foreach ($labs as $labId) {
                    $exists = DB::table('supervisor_labs')
                                ->where('user_id', $spvId)
                                ->where('lab_id', $labId)
                                ->exists();
                                
                    if (!$exists) {
                        DB::table('supervisor_labs')->insert([
                            'user_id' => $spvId,
                            'lab_id' => $labId
                        ]);
                    }
                }
            }

            return response()->json(['message' => 'Akun Dummy Berhasil Dibuat / Sudah Ada!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Setup gagal.', 'error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 2. LOGIN
    // ==========================================
    public function login(Request $request)
    {
        try {
            $user = DB::table('users')->where('email', $request->email)->first();
            
            // LOGIKA BARU: Gunakan password_verify bawaan PHP agar kompatibel dengan Node.js
            if (!$user || !password_verify($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid Email or Password'], 401);
            }

            // UPDATE last_login
            DB::table('users')->where('user_id', $user->user_id)->update([
                'last_login' => DB::raw('GETDATE()')
            ]);

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->user_id,
        'username' => $user->username,
        'email' => $user->email, // <--- TAMBAHKAN BARIS INI
        'role' => $user->role
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }
}