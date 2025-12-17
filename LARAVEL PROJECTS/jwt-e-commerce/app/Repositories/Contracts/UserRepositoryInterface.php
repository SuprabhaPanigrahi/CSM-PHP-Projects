<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Get all users with optional filters
     */
    public function getAll(array $filters = []): Collection;

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User;

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Create new user
     */
    public function create(array $data): User;

    /**
     * Update user
     */
    public function update(int $id, array $data): User;

    /**
     * Delete user
     */
    public function delete(int $id): bool;

    /**
     * Get users by role
     */
    public function getByRole(string $role): Collection;

    /**
     * Get users by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Count users by role
     */
    public function countByRole(string $role): int;

    /**
     * Check if email exists
     */
    public function emailExists(string $email, ?int $excludeId = null): bool;
}