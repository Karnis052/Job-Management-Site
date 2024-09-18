<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        try{
            $posts = Post::orderBy("id","desc")->paginate(10);
            return response()->json($posts, 201);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
    public function getOne($id)
    {
        try{
            $post = Post::findOrFail($id);
            return response()->json([
                'data'=> $post
                ],200);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
    public function create(Request $request)
    {
       
        try{
            $validatedData = $request->validate([
                'type'=> 'string|required',
                'title'=> 'string|required|max:255',
                'description'=> 'string',
                'salary' => 'string',
                'location' => 'string',
                'company.name' => 'string',
                'company.description' => 'string',
                'company.contactEmail' => 'email|required',
                'company.contactPhone' => 'string',
                'user_id' => 'integer|exists:users,id'
            ]);
            $companyDetails = $validatedData['company']??[];
            $postData = array_merge($validatedData,[
                'company_name' => $companyDetails['name'] ?? null,
                'company_description' => $companyDetails['description'] ?? null,
                'company_contact_email' => $companyDetails['contactEmail'] ?? null,
                'company_contact_phone' => $companyDetails['contactPhone'] ?? null
            ],
            [
                'user_id' =>1,
            ]);
        
            // dd($postData);
            $post = Post::create($postData);
            return response()->json([
                'data' => $post,
                'message' => 'post created successfully'
            ], 201);

        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function update(Request $request)
    {
        try{
            $post = Post::findOrFail($request->id);
            $validatedData = $request->validate([
                'type'=> 'string|required',
                'title'=> 'string|required|max:255',
                'description'=> 'string',
                'salary' => 'string',
                'location' => 'string',
                'company.name' => 'string',
                'company.description' => 'string',
                'company.contactEmail' => 'email|required',
                'company.contactPhone' => 'string',
                'user_id' => 'integer|exists:users,id'
            ]);
            $companyDetails = $validatedData['company']??[];
            $postData = array_merge($validatedData,[
                'company_name' => $companyDetails['name']??null,
                'company_description' => $companyDetails['description']??null,
                'company_contact_email' => $companyDetails['contactEmail'] ?? null,
                'company_contact_phone' => $companyDetails['contactPhone'] ?? null
            ]);
            $post->update($postData);
            return response()->json([
                'data' => $post,
                'message' => 'post updated successfully'
            ], 200);

        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
    
    public function delete($id)
    {
        try{
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json([
                'message' => 'post deleted successfully',
            ], 204);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
