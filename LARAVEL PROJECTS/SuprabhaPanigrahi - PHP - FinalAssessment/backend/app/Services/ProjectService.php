<?php

namespace App\Services;

use App\Repositories\ProjectRepository;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getAllProjects()
    {
        return $this->projectRepository->getProjectsWithCreator();
    }

    public function getProjectById($id)
    {
        return $this->projectRepository->find($id);
    }

    public function createProject(array $data)
    {
        return $this->projectRepository->create($data);
    }

    public function updateProject($id, array $data)
    {
        return $this->projectRepository->update($id, $data);
    }

    public function deleteProject($id)
    {
        return $this->projectRepository->delete($id);
    }

    public function getProjectsByEmployee($employeeId)
    {
        return $this->projectRepository->getProjectsByEmployee($employeeId);
    }
}