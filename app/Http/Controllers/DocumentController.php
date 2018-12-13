<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{

    public function store(Request $request, Task $task){
        
        foreach ( $request->file('files') as $file) {
            $fileName = $file->getClientOriginalName();
            //dd($name);
        	$path = $file->store(
	            'documents'
	        );
            
	        $task->documents()->create([
                'doc_name' => $fileName,
                'link' => $path,
			]);
        }

        return response([
            'status' => 'success',
            // 'data' => $path
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
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


        return response([
            'status' => 'success'
        ]);
    }
}
