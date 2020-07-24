<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \App\User::find($request->user()->id);

        $created_by = Task::where('created_by', $user->id)->pluck('tasks.id')->toArray();
        $assigned_to = $user->tasks()->pluck('tasks.id')->toArray();

        $ids = array_merge($created_by, $assigned_to);

        $tasks = Task::whereIn('id', $ids)->get()->toArray();

        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = \App\Status::all();
        $users = \App\User::select('id', 'name')->get();

        return response()->json(
            [
                'statuses' => $statuses,
                'users' => $users
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            "assignees.*"  => "required|exists:users,id",
            "status"  => "exists:task_statuses,id",
        ]);

        try {
            $task = new Task;
            $task->name = $request->name;
            $task->description = $request->description;
            $task->status_id = $request->status;
            $task->created_by = $request->user()->id;
            $task->save();
            $task->users()->sync($request->assignees);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }

        return response()->json(['task' => $task], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->json(['task' => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    public function search(Request $request){
        if($request->keyword === null){
            $user = \App\User::find($request->user()->id);

            $created_by = Task::where('created_by', $user->id)->pluck('tasks.id')->toArray();
            $assigned_to = $user->tasks()->pluck('tasks.id')->toArray();

            $ids = array_merge($created_by, $assigned_to);

            $tasks = Task::whereIn('id', $ids)->get()->toArray();
        }else{
            $validatedData = $request->validate([
                'keyword' => 'string|max:255',
            ]);
    
            $tasks = \App\Task::where('name', 'like', '%' . $request->keyword . '%')->get();
        }

        return response()->json(['tasks' => $tasks], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $user = \App\User::find($request->user()->id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:65535',
            "assignees.*"  => "required|exists:users,id",
            "status"  => "exists:task_statuses,id",
        ]);
        
        if($task->created_by === $user->id){
            try {
                $task->name = $request->name;
                $task->description = $request->description;
                $task->status_id = $request->status;
                $task->created_by = $request->user()->id;
                $task->save();
                $task->users()->sync($request->assignees);
            } catch (\Illuminate\Database\QueryException $ex) {
                return response()->json(['error' => $ex->getMessage()], 500);
            }
        }else{
            return response()->json(['error' => 'Unauthorized Access'], 401);
        }

        return response()->json(['task' => $task], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
    {
        if($request->user()->id === $task->created_by){
            $task->delete();
        }else{
            return response()->json(['message' => 'Unauthorized access'], 201);
        }
        return response()->json(['message' => 'Task successfully deleted'], 200);
    }
}
