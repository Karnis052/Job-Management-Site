<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    //
    public function index()
    {
        try{
            $users = User::limit(10)->get();
            return response()->json([
                'data' => $users
            ],200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()],404);
        }
        
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

    public function login(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $user = User::where('email', $validatedData['email'])->first();
            if($user && Hash::check($validatedData['password'], $user->password))
            {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' =>$user
                ], 200);
            }
            throw ValidationException::withMessages([
                'email'=> 'The provided creditials are incorrect',
            ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message'=> 'User deleted successfully',
                ],200);

        }catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function getUserPosts(Request $request)
    {
        try {
            // Ensure the user is authenticated
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            $user = Auth::user();
            $posts = $user->posts()
                ->orderBy('id', 'desc')
                ->paginate(10);
            return response()->json($posts, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

}
