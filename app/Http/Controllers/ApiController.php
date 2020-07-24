<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{
    public function statuses(){
        $statuses = \App\Status::all()->toArray();
        return response()->json(['statuses' => $statuses], 200);
    }

    public function getUsersCount(){
        $count = \App\User::all()->count();

        $users = \App\User::all();
        
        $tcount=[];
        foreach($users as $user){
            $tcount[] = $user->createdTasks()->count();
        }

        $tcount = array_filter($tcount);
        $average = array_sum($tcount)/count($tcount);

        $acount = [];
        foreach($users as $user){
            $acount[] = $user->tasks()->count();
        }

        $acount = array_filter($acount);
        $a_average = array_sum($acount)/count($acount);

        return response()->json([
            'users' => $count,
            'avg' => $average,
            'avg_per_usr' => $a_average
        ], 200);
    }

    public function setStatus($id, Request $request){
        $task = \App\Task::find($id);
        $ids = $task->users()->pluck('users.id');
        $ids[] = $creator = $task->created_by;
        if(in_array($request->user()->id,$ids->toArray())){
            $task->status_id = $request->status_id;

            $task->save();
            return response()->json(['task'=>$task],200);
        }else{
            return response()->json(['message'=> 'Unauthorized access'], 401);
        }
        
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['The provided credentials are incorrect.']
            ], 404);
        }

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ];

        return response($response, 201);
    }
}
