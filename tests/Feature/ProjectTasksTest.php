<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_project_can_have_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $body = $this->faker->sentence;

        $this->post(route('tasks.store', $project), ['body' => $body])
            ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('tasks', ['body' => $body]);

        $this->get(route('projects.show', $project))->assertSee($body);
    }

    public function test_a_task_require_a_body()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $task = Task::factory()->raw(['body' => '']);

        $this->post(route('tasks.store', $project), $task)->assertSessionHasErrors('body');
    }

    public function test_only_owner_of_project_can_add_task()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $task = Task::factory()->raw();

        $this->post(route('tasks.store', $project), $task)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $task);
    }

    public function test_only_owner_of_task_can_be_updated()
    {
        $project =  ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $body = $this->faker->sentence;

        $this->put(route('tasks.update', $project->tasks[0]), ['body' => $body, 'completed' => true])
            ->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('tasks', [
            'completed' => true,
            'body' => $body
        ]);

        $this->get(route('projects.show', $project))->assertSee($body);
    }

    public function test_a_task_when_updated_require_a_body()
    {
        $this->signIn();

        $task = Task::factory()->create();

        $this->put(route('tasks.update', $task), ['body' => ''])
            ->assertSessionHasErrors('body');
    }


    public function test_only_owner_of_task_only_can_update()
    {
        $this->signIn();

        $task = Task::factory()->create();

        $this->put(route('tasks.update', $task), ['body' => 'new new'])
            ->assertStatus(403);
    }

    public function test_task_can_marked_completed()
    {
        $project =  ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $attributes =
            [
                'completed' => true,
                'body' => $this->faker->sentence
            ];

        $this->put(route('tasks.update', $project->tasks->first()), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    public function test_task_can_marked_incomplete()
    {
        $project =  ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $attributes = [
            'body' => $this->faker->sentence
        ];

        $this->put(route('tasks.update', $project->tasks->first()), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }
}
