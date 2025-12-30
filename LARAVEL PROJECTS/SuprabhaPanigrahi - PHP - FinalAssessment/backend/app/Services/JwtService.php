<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Exception;

class JwtService
{
    private $secretKey;
    private $algorithm;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET', 'your-secret-key-change-this');
        $this->algorithm = 'HS256';
    }

    /**
     * Generate JWT token
     */
    public function generateToken($user)
    {
        $payload = [
            'iss' => 'employee-management', 
            'aud' => 'employee-management', 
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), 
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    /**
     * Validate JWT token
     */
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (ExpiredException $e) {
            return ['error' => 'Token has expired'];
        } catch (SignatureInvalidException $e) {
            return ['error' => 'Invalid token signature'];
        } catch (Exception $e) {
            return ['error' => 'Invalid token'];
        }
    }

    /**
     * Extract token from header
     */
    public function extractToken($request)
    {
        $header = $request->header('Authorization');
        
        if (!$header) {
            return null;
        }
        
        if (preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}