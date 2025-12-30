<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\JwtService;
use App\Models\User;
use App\Models\Employee;

class AuthController extends Controller
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * User Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Find user
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if user is active
        if (!$user->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'Account is inactive'
            ], 401);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Generate token
        $token = $this->jwtService->generateToken($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ]
        ]);
    }

    /**
     * User Registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,manager,employee',
            'name' => 'required_if:role,employee|string|max:255',
            'email' => 'required_if:role,employee|email|unique:employees,email'
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'username' => $request->username,
                'password_hash' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => 1
            ]);

            if ($request->role === 'employee') {
                $employee = Employee::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'created_by' => $user->id 
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMyEmployeeId(Request $request)
    {
        $authUser = $request->input('auth_user');
        
        $employee = Employee::where('email', $authUser['username'])->first();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee record not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'employee_id' => $employee->id,
            'employee_name' => $employee->name
        ]);
    }

    public function me(Request $request)
    {
        $authUser = $request->input('auth_user');
        
        $employee = Employee::where('email', $authUser['username'])->first();
        if ($employee) {
            $authUser['employee_id'] = $employee->id;
            $authUser['employee_name'] = $employee->name;
        }

        return response()->json([
            'status' => 'success',
            'user' => $authUser
        ]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }
}