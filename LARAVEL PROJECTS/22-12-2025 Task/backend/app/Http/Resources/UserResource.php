<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'role_display' => ucfirst($this->role),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'teams' => $this->whenLoaded('teams', function () {
                return $this->teams->pluck('name');
            }),
            'managed_teams' => $this->whenLoaded('managedTeams', function () {
                return $this->managedTeams->pluck('name');
            }),
        ];
    }
}