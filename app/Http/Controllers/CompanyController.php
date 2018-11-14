<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\Company;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $company = Company::with('users.profile')
        ->find($user->company_id);
    
        return response([
            'status' => 'success',
            'data' => $company
        ]);

        
    }
}
