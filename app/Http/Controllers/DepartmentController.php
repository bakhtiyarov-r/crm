<?php

namespace App\Http\Controllers;

use Auth;
use App\Department;
use Illuminate\Http\Request;
use App\Http\Requests\ViewDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Requests\DeleteDepartmentRequest;
use App\Http\Requests\StoreDepartmentRequest;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Auth::user()->company->departments->all();
        return response([
            'status' => 'success',
            'data' => $departments
        ]);
    }

    // public function show(ViewDepartmentRequest $request, Department $department)
    // {
    //     $department = $department->load('profile');
    //     return response([
    //         'status' => 'success',
    //         'data' => $department
    //     ]);
    // }

    public function store(StoreDepartmentRequest $request)
    { 
        $department = new Department($request->all());
        Auth::user()->company->departments()->save($department);
        $data = Auth::user()->company->departments->all();

        return response([
            'status' => 'success',
            'data' => $data
           ], 200);
    }

 //    public function update(UpdateDepartmentRequest $request, Department $department)
	// {
 //        $department->fill($request->all())->save();
 //        $data = $department->get();
	//     return response([
	//         'status' => 'success',
 //            'data' => $data
	//        ], 200);
	// }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(DeleteDepartmentRequest $request, Department $department)
    // {
    //     $department->delete();
    //     return response([
    //         'status' => 'success',
    //         'redirect' => '/company'
    //     ]);
    // }
}
