<?php

namespace Tests\Feature;

use App\Models\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GuestProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_guest_cannot_view_single_project()
    {
        $project = Project::factory()->create();

        $this->get(route('projects.show', $project))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_view_projects()
    {
        $this->get(route('projects.index'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_create_project()
    {
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post(route('projects.store'), $attributes)->assertRedirect(route('login'));
    }

    public function test_guest_cannot_update_project()
    {
        $project = Project::factory()->create();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->put(route('projects.update', $project), $attributes)->assertRedirect(route('login'));
    }

    public function test_guest_cannot_see_view_of_create_project()
    {
        $this->get(route('projects.create'))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_see_view_of_edit_project()
    {
        $project = Project::factory()->create();

        $this->get(route('projects.edit', $project))->assertRedirect(route('login'));
    }

    public function test_guest_cannot_delete_project()
    {
        $project = Project::factory()->create();

        $this->delete(
            route('projects.destroy', $project)
        )->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(
            route('projects.destroy', $project)
        )->assertStatus(403);
    }
}
