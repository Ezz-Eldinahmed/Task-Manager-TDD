<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_project_belongs_user()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->assertInstanceOf(User::class, $project->user);
    }

    public function test_project_can_add_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $task = $project->addTask('task test');

        $this->assertCount(1, $project->tasks);

        $this->assertTrue($project->tasks->contains($task));
    }

    public function test_project_can_invite_user()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $project->invite($user = User::factory()->create());

        $this->assertTrue($project->members->contains($user));
    }
}
