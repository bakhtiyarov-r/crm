<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Task;
use App\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Task::with('user.profile')
        ->whereHas('user', function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        })
        ->get();
        return response([
            'status' => 'success',
            'data' => $task
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $task = new Task;
        $task->user_id = Auth::user()->id;
        $task->project_id = $id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->deadline = $request->deadline;
        $task->immediate = $request->immediate;
        $task->opened = true;
        $task->done = false;
        $task->drafts = $request->drafts;
        $task->canceled = false;
        $task->save();
        $task->executors()->sync($request->responsible);

        return response([
            'status' => 'success',
            'data' => $task
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
        $task = Task::with(['user.profile', 'executors.profile'])
        ->whereHas('user', function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        })
        ->find($id);
        
        return response([
            'status' => 'success',
            'data' => $task
        ]);



    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        
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
        $task = Task::where('user_id', Auth::user()->id)->find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->deadline = $request->deadline;
        $task->opened = $request->opened;
        $task->immediate = $request->immediate;
        $task->drafts = $request->drafts;
        $task->canceled = $request->canceled;
        $task->executors()->sync($request->responsible);
        $task->save();

        return response([
            'status' => 'success',
            'data' => $task
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
        $task = Task::where('user_id', Auth::user()->id)->find($id)->delete();
        return response([
            'status' => 'success',
            'redirect' => '/tasks'
        ]);
    }
}
