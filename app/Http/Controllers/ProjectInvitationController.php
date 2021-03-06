<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectInvitationController extends Controller
{
    public function store(ProjectInvitationRequest $request, Project $project)
    {
        $user = User::whereEmail($request->email)->first();

        $project->invite($user);

        return redirect()->route('projects.show', $project);
    }
}
