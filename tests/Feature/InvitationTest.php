<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_can_invite_users()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $userToInvite = User::factory()->create();

        $this->post(route('project.invite', $project), [
            'email' => $userToInvite->email
        ])->assertRedirect(route('projects.show', $project));

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_users_have_access_can_update_project_details()
    {
        $project = Project::factory()->create();

        $project->invite($user = User::factory()->create());

        $this->signIn($user);

        $this->post(route('tasks.store', $project), [
            'body' => 'new task'
        ]);

        $this->assertDatabaseHas('tasks', ['body' => 'new task']);
    }

    public function test_the_email_invited_must_be_in_database()
    {
        $this->withExceptionHandling();

        $project = ProjectFactory::ownedBy($user = $this->signIn())->create();

        $this->post(route('project.invite', $project), [
            'email' => 'email@email.com'
        ])->assertSessionHasErrors(['email' => 'User Invite Must Be Exists'], null, 'invitations');
    }

    public function test_only_owner_of_project_can_add_users()
    {
        $this->withExceptionHandling();

        $project = Project::factory()->create();

        $this->signIn($user = User::factory()->create());

        $assertInvitationForbidden = function () use ($project) {
            $this->post(route('project.invite', $project), [
                'email' => User::factory()->create()->email
            ])->assertStatus(403);
        };

        $assertInvitationForbidden();

        $project->invite($user);

        $assertInvitationForbidden();
    }
}
