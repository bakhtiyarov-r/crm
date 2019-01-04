<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    public function store(UpdateTaskRequest $request, Task $task){

        // $project = $task->project_id;
        // $company = $task->company_id;
        
        foreach ( $request->file('files') as $file) {
            $fileName = $file->getClientOriginalName();
        	$path = $file->store(
	            'documents'
	        );

	        $task->documents()->create([
                //'company_id' => $company,
                //'project_id' => $project,
                'doc_name' => $fileName,
                'link' => $path,
			]);
        }

        $documents = $task->load('documents');

        return response([
            'status' => 'success',
            'data' => $documents
            // 'data' => $path
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UpdateTaskRequest $request, Task $task)
    {
        $documents = $request->documents;
        $list = array();

        foreach ( $documents as $value) {
            $document = $task->documents()->find($value);
            $link = $document->link;
            
            array_push($list, $link);
            $document->delete();
        }

        Storage::delete($list);

        $documents = $task->load('documents');

        return response([
            'status' => 'success',
            'data' => $documents
        ]);
    }
}
