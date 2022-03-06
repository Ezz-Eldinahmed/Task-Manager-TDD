<?php

namespace Tests\Feature;

use App\Models\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_project_generate_activities()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->assertCount(1, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('project_created', $activity->description);

            $this->assertNull($activity->changes);
        });
    }

    public function test_updating_a_project_generate_activities()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $originalTitle = $project->title;

        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($originalTitle) {

            $this->assertEquals('project_updated', $activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'changed']
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    public function test_task_added_to_project_generates_activity()
    {
        $project = ProjectFactory::withTasks(1)->ownedBy($this->signIn())->create();

        tap($project->activity->last(), function ($activity) use ($project) {

            $this->assertEquals('task_created', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);

            $this->assertEquals($project->tasks->last()->body, $activity->subject->body);
        });

        $this->assertCount(2, $project->activity);
    }

    public function test_task_when_completed_generate_activity()
    {
        $project = ProjectFactory::withTasks(1)->ownedBy($this->signIn())->create();

        $this->put(route('tasks.update', $project->tasks->first()), [
            'completed' => true,
            'body' => 'new task'
        ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity) {

            $this->assertEquals('task_completed', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);

            $this->assertEquals('new task', $activity->subject->body);
        });
    }

    public function test_task_when_incompleted_generate_activity()
    {
        $project = ProjectFactory::withTasks(1)->ownedBy($this->signIn())->create();

        $this->put(route('tasks.update', $project->tasks->first()), [
            'completed' => false,
            'body' => 'new task'
        ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity) {

            $this->assertEquals('task_incompleted', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);

            $this->assertEquals('new task', $activity->subject->body);
        });
    }
}
