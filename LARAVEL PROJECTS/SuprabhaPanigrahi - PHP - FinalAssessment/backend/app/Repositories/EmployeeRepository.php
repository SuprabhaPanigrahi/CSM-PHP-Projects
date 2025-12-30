<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Repositories\BaseRepository;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    public function getEmployeesWithCreator()
    {
        return $this->model->with('creator')->get();
    }

    public function getEmployeesByProject($projectId)
    {
        return $this->model->whereHas('projects', function ($query) use ($projectId) {
            $query->where('projects.id', $projectId);
        })->get();
    }
}