<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

//    public function adminDashboard(){
//        return view('admin');
//    }
//
//    public function employeeDashboard(){
//        return view('employee');
//    }

//    public function adminDashboard(){
//        return view('dashboard');
//    }

    public function employeeDashboard(){
        return view('dashboard');
    }
}
