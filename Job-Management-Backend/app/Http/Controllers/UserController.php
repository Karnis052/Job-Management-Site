<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::limit(10)->get();
        return response()->json(['data' => $users],200);
    }
    public function getOne($id)
    {
        try{
            $user = User::findOrFail($id);
            return response()->json(['data' => $user], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()],404);
        }
    }
    public function create(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'name'=> 'string|required',
                'email'=> 'email|required|unique:users,email',
                'password'=> 'string|required|min:4',
            ]);
            $user = User::create([
                'name'=> $validatedData['name'],
                'email'=> $validatedData['email'],
                'password'=> bcrypt($validatedData['password']),
            ]);
            return response()->json([
                'data'=> $user->only(['name','email']),
                'message' => 'User created successfully',
            ],201);
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()],500);
        }
    }

    public function update(Request $request)
    {
        try{
            $user = User::findOrFail($request->id);
            $validatedData = $request->validate([
                'name'=> 'string|required',
                'email'=> 'email|required|unique:users,email,' . $user->id,
            ]);
            if(isset($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }
            $user->update($validatedData);
            return response()->json([ 
                'data'=> $user->only(['name','email']),
                'message'=> 'User updated successfully',
                ],200);
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()],500);
        }
    }

    public function delete($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message'=> 'User deleted successfully'
                ],204);
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()],500);  
        }
    }
}
