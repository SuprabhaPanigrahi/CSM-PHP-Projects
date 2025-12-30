<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'priority_display' => ucfirst($this->priority),
            'due_date' => $this->due_date->format('Y-m-d'),
            'due_date_formatted' => $this->due_date->format('M d, Y'),
            'status' => $this->status,
            'status_display' => str_replace('_', ' ', ucfirst($this->status)),
            'is_overdue' => $this->isOverdue(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_formatted' => $this->created_at->format('M d, Y h:i A'),
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'can_edit' => $this->when(auth()->check(), function () {
                $user = auth()->user();
                if ($user->isAdmin()) return true;
                if ($user->isManager()) {
                    $teamMembers = $user->teams->flatMap->members->pluck('id')->unique();
                    return $this->created_by === $user->id || 
                           $teamMembers->contains($this->assigned_to);
                }
                return $this->assigned_to === $user->id;
            }),
        ];
    }
}