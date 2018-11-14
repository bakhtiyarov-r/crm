<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::with('user.profile')
        ->where('company_id', Auth::user()->company_id)
        ->get();
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
    public function store(Request $request)
    {
        $project = new Project;
        $project->company_id = Auth::user()->company_id;
        $project->user_id = Auth::user()->id;
        $project->title = $request->title;
        $project->description = $request->description;
        $project->opened = true;
        $project->closed = false;
        $project->save();
        $project->executors()->sync($request->responsible);

        return response([
            'status' => 'success',
            'data' => $project
           ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::with(['user.profile', 'executors.profile'])
        ->where('company_id', Auth::user()->company_id)
        ->find($id);
        
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
    public function update(Request $request, $id)
    {
        $project = Project::where('company_id', Auth::user()->company_id)->find($id);
        $project->title = $request->title;
        $project->description = $request->description;
        $project->opened = $request->opened;
        $project->save();
        $project->executors()->sync($request->responsible);
        return response([
            'status' => 'success',
            'data' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::where('company_id', Auth::user()->company_id)->find($id)->delete();
        return response([
            'status' => 'success',
            'redirect' => '/projects'
        ]);
    }


    public function showTasks($id)
    {
        $project = Project::with('tasks.user.profile')
        ->where('company_id', Auth::user()->company_id)
        ->find($id);
        
        return response([
            'status' => 'success',
            'data' => $project
        ]);
    }

    
}
