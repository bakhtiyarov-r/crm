<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Project;
use App\Company;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\ViewProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\DeleteProjectRequest;
use App\Http\Requests\StoreProjectRequest;

use Illuminate\Support\Facades\Notification;
use App\Notifications\ProjectAddedToExecutor;
use App\Notifications\ProjectEdited;

class ProjectController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Auth::user()->company->projects->load('user.profile');
        return response([
            'status' => 'success',
            'data' => $project
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request, Company $company )
    {
        
        $project = new Project($request->all());
        $project->user_id = Auth::user()->id;
        $company->projects()->save($project);
        $project->executors()->sync($request->responsible);
        $data = $company->projects->load('user.profile');
        $resp = $request->responsible;
        $users = User::find($resp);
        Notification::send($users, new ProjectAddedToExecutor($project));

        return response([
            'status' => 'success',
            'data' => $data
           ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ViewProjectRequest $request, Project $project)
    {
        $project = $project->load(['user.profile', 'executors.profile', 'tasks.user.profile']);
        return response([
            'status' => 'success',
            'data' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->fill($request->all())->save();
        $project->executors()->sync($request->responsible);
        $data = $project->load(['user.profile', 'executors.profile', 'tasks.user.profile']);
        $resp = $request->responsible;
        $users = User::find($resp);
        Notification::send($users, new ProjectEdited($project));
        return response([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteProjectRequest $request, Project $project)
    {
            $project->delete();
            return response([
                'status' => 'success',
                'redirect' => '/projects'
            ]);
    }
    
}
