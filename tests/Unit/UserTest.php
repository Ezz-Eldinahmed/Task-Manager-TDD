<?php

namespace Tests\Unit;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_has_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    public function test_user_has_invited_and_its_own_projects()
    {
        $user = $this->signIn();

        ProjectFactory::ownedBy($user)->create();

        $this->assertCount(1, $user->availableProject());

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $project1 = ProjectFactory::ownedBy($user1)->create();

        $project1->invite($user2);

        $this->assertCount(1, $user->availableProject());

        $project1->invite($user);

        $this->assertCount(2, $user->availableProject());
    }
}
