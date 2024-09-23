<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        try{ 
            $applications = Application::orderBy('id')->paginate(10);
            return response()->json([
                'data' => $applications,
            ], 200);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    public function getOne(Request $request)
    {
        try{
            $application = Application::findOrFail($request->id);
            return response()->json([
                'data' => $application,
            ], 200);
        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'firstName' => 'required|string',
                'lastName' => 'string|required',
                'email' => 'email|required',
                'phone'=> 'string|required',
                'major'=> 'string|sometimes',
                'university' => 'string|sometimes',
                'resume' => 'required|file|mimes:pdf|max:4096',
                'post_id' => 'required|exists:posts,id'

            ]);
            if($request->hasFile('resume')){
                $path =  $request->file('resume')->store('resumes', 'public');
                $validatedData['resume_path'] = $path;
            }
            $application = Application::create($validatedData);
            return response()->json([
                'data' => $application,
                'message' => 'Application created successfully'
            ], 201);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }

    
}






