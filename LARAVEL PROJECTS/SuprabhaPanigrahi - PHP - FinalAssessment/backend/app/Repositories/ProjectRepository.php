<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\BaseRepository;

class ProjectRepository extends BaseRepository
{
    public function __construct(Project $project)
    {
        $this->model = $project;
    }

    public function getProjectsWithCreator()
    {
        return $this->model->with('creator')->get();
    }

    public function getProjectsByEmployee($employeeId)
    {
        return $this->model->whereHas('employees', function ($query) use ($employeeId) {
            $query->where('employees.id', $employeeId);
        })->get();
    }
}