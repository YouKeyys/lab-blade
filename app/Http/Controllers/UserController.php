<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==========================================
    // 1. GET ALL USERS
    // ==========================================
    public function index()
    {
        try {
            $query = "
                SELECT 
                    u.user_id, u.username, u.email, u.role, u.created_at, u.last_login,
                    STRING_AGG(l.lab_name, '||') as assigned_labs
                FROM users u
                LEFT JOIN supervisor_labs sl ON u.user_id = sl.user_id
                LEFT JOIN labs l ON sl.lab_id = l.lab_id
                GROUP BY u.user_id, u.username, u.email, u.role, u.created_at, u.last_login
                ORDER BY u.created_at DESC
            ";
            
            $result = DB::select($query);

            $formattedUsers = array_map(function($user) {
                return [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'last_login' => $user->last_login,
                    'assignedLabs' => $user->assigned_labs ? explode('||', $user->assigned_labs) : []
                ];
            }, $result);

            return response()->json($formattedUsers);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch users', 'error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 2. CREATE NEW USER
    // ==========================================
    public function store(Request $request)
    {
        try {
            $checkEmail = DB::table('users')->where('email', $request->email)->first();
            if ($checkEmail) {
                return response()->json(['message' => 'Email is already in use!'], 400);
            }

            $hashedPassword = Hash::make($request->password);

            $newUserId = DB::table('users')->insertGetId([
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'password' => $hashedPassword
            ]);

            if ($request->role === 'supervisor' && !empty($request->assignedLabs)) {
                $this->assignLabsToSupervisor($newUserId, $request->assignedLabs);
            }

            return response()->json(['message' => 'User created successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 3. UPDATE USER
    // ==========================================
    public function update(Request $request, $id)
    {
        try {
            $updateData = [
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role
            ];

            // Update password only if provided
            if (!empty($request->password) && trim($request->password) !== '') {
                $updateData['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('user_id', $id)->update($updateData);

            // Clean up old lab assignments
            DB::table('supervisor_labs')->where('user_id', $id)->delete();

            // Insert new lab assignments
            if ($request->role === 'supervisor' && !empty($request->assignedLabs)) {
                $this->assignLabsToSupervisor($id, $request->assignedLabs);
            }

            return response()->json(['message' => 'User updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 4. DELETE USER
    // ==========================================
    public function destroy($id)
    {
        try {
            DB::table('users')->where('user_id', $id)->delete();
            return response()->json(['message' => 'User deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // HELPER FUNCTION
    // ==========================================
    private function assignLabsToSupervisor($userId, $labNamesArray)
    {
        foreach ($labNamesArray as $labName) {
            $lab = DB::table('labs')->where('lab_name', $labName)->first();
            if ($lab) {
                DB::table('supervisor_labs')->insert([
                    'user_id' => $userId,
                    'lab_id' => $lab->lab_id
                ]);
            }
        }
    }
}