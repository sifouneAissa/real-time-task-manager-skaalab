<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //

    public function index(){

        $viewAllTasks = auth()->user()->hasPermissionTo('view all tasks');
        $builder = Task::query();
        if($viewAllTasks){
            $tasks = $builder->get();
        }
        else {
            $tasks = $builder->where('assigned_to',auth()->user()->id)->get();
        }
        return Inertia::render('Dashboard',[
            'tasks' => $tasks
        ]);
    }
}
