<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_a_user()
    {
        $user = $this->signIn();

        $project =  ProjectFactory::ownedBy($user)->withTasks(1)->create();

        $this->assertEquals($user->id, $project->user->id);

        $this->assertInstanceOf(User::class, $project->activity->first()->user);
    }
}
