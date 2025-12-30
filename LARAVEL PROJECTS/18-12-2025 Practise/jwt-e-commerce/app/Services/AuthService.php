<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     */
    public function register(array $data): array
    {
        // Business Logic: Check if email already exists
        if ($this->userRepository->emailExists($data['email'])) {
            throw new \Exception('Email already registered');
        }

        // Business Logic: Hash password
        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'customer';
        $data['status'] = 'active';
        $data['email_verified_at'] = now();

        // Create user via repository
        $user = $this->userRepository->create($data);

        // Business Logic: Generate JWT token
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Authenticate user and generate token
     */
    public function login(array $credentials): array
    {
        // Business Logic: Find user by email
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user) {
            throw new \Exception('Invalid credentials');
        }

        // Business Logic: Verify password
        if (!Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        // Business Logic: Check user status
        if ($user->status !== 'active') {
            throw new \Exception('Account is ' . $user->status);
        }

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Get authenticated user
     */
    public function me(): User
    {
        return JWTAuth::user();
    }

    /**
     * Refresh JWT token
     */
    public function refresh(): array
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Logout user (invalidate token)
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(User $user, string|array $roles): bool
    {
        if (is_string($roles)) {
            return $user->role === $roles;
        }

        return in_array($user->role, $roles);
    }

    /**
     * Check if user is active
     */
    public function isActive(User $user): bool
    {
        return $user->status === 'active';
    }
}