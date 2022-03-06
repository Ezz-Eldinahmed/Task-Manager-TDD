<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_project()
    {
        $this->signIn();

        $task = Task::factory()->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }
}
