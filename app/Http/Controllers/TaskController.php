<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Task;
use App\Project;
use App\User;

use Illuminate\Http\Request;
use App\Http\Requests\ViewTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\DeleteTaskRequest;

use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskAddedToExecutor;
use App\Notifications\TaskEdited;
use App\Notifications\TaskClosed;
use App\Notifications\TaskDone;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Auth::user()->company->tasks->load(['user.profile', 'executors.profile']);
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
    public function store(StoreTaskRequest $request, Project $project)
    {
        $task = new Task($request->all());
        $task->user_id = Auth::user()->id;
        $task->company_id = Auth::user()->company_id;
        $project->tasks()->save($task);
        $task->executors()->sync($request->responsible);
        $data = $project->load(['user.profile', 'executors.profile', 'tasks.user.profile']);
        $resp = $request->responsible;
        $users = User::find($resp);
        Notification::send($users, new TaskAddedToExecutor($task));

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
    public function show(ViewTaskRequest $request, Task $task)
    {
        $task = $task->load(['user.profile', 'executors.profile', 'documents']);
        
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

    public function doneTask(Request $request, Task $task)
    {
        $task->fill($request->all())->save();
        $user = User::find($task->user_id);
        $users = $task->executors;

        if ($task->done) {
            Notification::send($user, new TaskDone($task));
        } else {
            Notification::send($users, new TaskDone($task));
        }

        return response([
            'status' => 'success'
        ]);
    }

    public function closeTask(UpdateTaskRequest $request, Task $task)
    {
        $task->fill($request->all())->save();
        $users = $task->executors;

        Notification::send($users, new TaskClosed($task));

        return response([
            'status' => 'success'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->fill($request->all())->save();
        $task->executors()->sync($request->responsible);
        $resp = $request->responsible;
        $users = User::find($resp);
        Notification::send($users, new TaskEdited($task));

        $data = $task->load(['user.profile', 'executors.profile', 'documents']);

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
    public function destroy(DeleteTaskRequest $request, Task $task)
    {
        $task->delete();
        return response([
            'status' => 'success',
            'redirect' => '/tasks'
        ]);
    }
}
