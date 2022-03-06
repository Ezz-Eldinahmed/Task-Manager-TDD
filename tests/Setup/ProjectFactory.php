<?php

namespace Tests\Setup;

use App\Models\Project;
use App\Models\Task;

class ProjectFactory
{
    protected $taskCount = 0;

    protected $user;

    public function create()
    {
        $project = Project::factory()->create(
            [
                'user_id' => $this->user ?? auth()->user()->id
            ]
        );

        Task::factory()->count($this->taskCount)->create(['project_id' => $project->id]);

        return $project;
    }

    public function withTasks($count)
    {
        $this->taskCount = $count;

        return $this;
    }

    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }
}
