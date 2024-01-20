<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{


    public function create()
    {

        $users = User::query()->whereNot('id',auth()->user()->id)->get([
            'id',
            'name'
        ]);

        return Inertia::render('Tasks/Create',compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
//            'status' => 'required|in:To Do,In Progress,Completed',
            'priority' => 'required|in:High,Medium,Low',
            'due_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->addHour()->toDateTimeString(),
            ],
            'assigned_to' => 'nullable|array',
        ]);

        $request->merge(['due_date' => Carbon::parse($request->get('due_date'))]);

        $data = $request->only('title','description','priority','due_date');

        $data['user_id'] = auth()->user()->id;

        $assigned_to = $request->get('assigned_to');

        if($assigned_to){
            foreach($assigned_to as $item){
                $data['assigned_to'] = $item['id'];
                $task = Task::create($data);
                event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'CREATED',$task));
            }
        }
        else {
            $data['assigned_to'] = auth()->user()->id;
            $task = Task::create($data);
            event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'CREATED',$task));
        }


    }

    public function show(Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'task' => $task,
        ]);
    }

    public function edit(Request $request,Task $task)
    {

        $users = User::query()->whereNot('id',auth()->user()->id)->get([
            'id',
            'name'
        ]);

        return Inertia::render('Tasks/Edit', [
            'users' => $users,
            'task' => $task
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
//            'status' => 'required|in:To Do,In Progress,Completed',
            'priority' => 'required|in:High,Medium,Low',
            'due_date' => [
                'required',
                'date',
                'after_or_equal:' . now()->addHour()->toDateTimeString(),
            ],
            'assigned_to' => 'required|array',
        ]);
        $request->merge(['due_date' => Carbon::parse($request->get('due_date'))]);
        $data = $request->only('title','description','priority','due_date');

        $data['user_id'] = auth()->user()->id;

        $assigned_to = $request->get('assigned_to');

        if($assigned_to){
            foreach($assigned_to as $item){
                $data['assigned_to'] = $item['id'];
                if($item['id'] === $task->assigned_to){
                    $task->update($data);
                    event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'UPDATED',$task));
                }
                else {
                    Task::create($data);
                    event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'CREATED',$task));
                }
            }
        }
        else {
            $data['assigned_to'] = auth()->user()->id;
            if(auth()->user()->id === $task->assigned_to){
                $task->update($data);
                event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'UPDATED',$task));
            }
            else {
                $task = Task::create($data);
                event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'CREATED',$task));
            }
        }

    }

    public function destroy(Task $task)
    {
        $ntask = $task->toArray();

        $task->delete();

        event(new \App\Events\TaskUpdateEvent([$task->assignedTo],'DELETED',$ntask));

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully.');
    }



    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:To Do,In Progress,Completed',
        ]);

        $task->update([
            'status' => $request->get('status')
        ]);

        if($task->status === "Completed") {
            $task->completed_at = now();
            $task->save();
        };

        event(new \App\Events\TaskUpdateEvent([$task->user->id === auth()->user()->id ? $task->assignedTo : $task->user ],'UPDATED',$task));
    }


}
