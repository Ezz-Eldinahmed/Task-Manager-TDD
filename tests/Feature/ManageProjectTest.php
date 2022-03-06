<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get(route('projects.create'))->assertStatus(200);

        $attributes = Project::factory()->raw(['user_id' => auth()->user()->id]);

        $this->followingRedirects()->post(route('projects.store'), $attributes);

        $this->assertDatabaseHas('projects', $attributes);

        $this->get(route('projects.index'))
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description']);
    }

    public function test_authenticated_user_can_view_their_projects()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->get(route('projects.show', $project))
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_user_cannot_view_others_projects()
    {
        $this->signIn();

        $project = Project::factory()->create();

        $this->get(route('projects.show', $project))
            ->assertStatus(403);
    }

    public function test_a_user_can_see_project_that_he_invited_in_dashboard()
    {
        $user = $this->signIn();

        $project = Project::factory()->create();

        $project->invite($user);

        $this->get(route('projects.index'))
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_a_project_require_a_title()
    {
        $this->signIn();

        $project =  Project::factory()->raw(['title' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('title');
    }

    public function test_a_project_require_a_description()
    {
        $this->signIn();

        $project =  Project::factory()->raw(['description' => '']);

        $this->post(route('projects.store'), $project)->assertSessionHasErrors('description');
    }

    public function test_only_authenticated_user_can_create_project()
    {
        $project =  Project::factory()->raw(['user_id' => null]);

        $this->post(route('projects.store'), $project)->assertRedirect(route('login'));
    }

    public function test_a_user_can_update_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->put(
            route('projects.update', $project),
            $attributes =
                [
                    'notes' => 'new note',
                    'title' => 'new title',
                    'description' => 'new description'
                ]
        )->assertRedirect(route('projects.show', $project));

        $this->assertDatabaseHas('projects',  $attributes);

        $this->get(route('projects.edit', $project))->assertStatus(200);

        $this->get(route('projects.show', $project))->assertSee($attributes);
    }

    public function test_a_user_can_update_a_project_notes()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->put(
            route('projects.update', $project),
            $attributes =
                [
                    'notes' => 'new note',
                ]
        );

        $this->assertDatabaseHas('projects',  $attributes);
    }

    public function test_a_user_can_delete_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->delete(
            route('projects.destroy', $project)
        )->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', $project->toArray());
    }

    public function test_only_owner_can_delete_project()
    {
        $userOwner = User::factory()->create();

        $project = ProjectFactory::ownedBy($userOwner)->create();

        $this->delete(
            route('projects.destroy', $project)
        )->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(
            route('projects.destroy', $project)
        )->assertStatus(403);

        $this->signIn($userOwner);

        $this->delete(
            route('projects.destroy', $project)
        )->assertRedirect(route('projects.index'));

        $project = ProjectFactory::ownedBy($userOwner)->create();

        $userInvited = User::factory()->create();

        $project->invite($userInvited);

        $this->signIn($userInvited);

        $this->delete(
            route('projects.destroy', $project)
        )->assertStatus(403);
    }
}
