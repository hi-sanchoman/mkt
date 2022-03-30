<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Auth;
use App\Models\Task;

class SubtasksController extends Controller
{
    //
    public function store(Request $request)
    {
        $task = Task::find($request->task_id);
        
    	$subtask = Subtask::create([
            'task_id' => $request->task_id,
            'title' => $request->title,
            'description' => $request->description,
            'start' => $request->start,
            'deadline' => $request->deadline,
            'status' => $request->substatus,
            'user_id' => $request->task_id == 0 ? Auth::user()->id : $task->user_id,
        ]);
        
        if($request->task_id != 0) {
            return redirect('/tasks/' . $request->task_id);
        } else {
            return redirect('/');
        }  
    }

    public function status(Request $request) {
        $item = Subtask::find($request->id);
        if($item) {
            $item->status = $request->status;
            $item->save();
        }
    }
}
