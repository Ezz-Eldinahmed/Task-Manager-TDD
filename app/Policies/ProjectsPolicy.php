<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectsPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Project $project)
    {
        return $user->is($project->user) || $project->members->contains($user);
    }

    public function manage(User $user, Project $project)
    {
        return $user->is($project->user);
    }
}
