<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

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

    public function listApplicationsForPost($postId)
    {
        try{
            $post = Post::findOrFail($postId);

            // if(Auth::id() !== $post->user_id)
            // {
            //     return response()->json(['error'=>'Unauthorized'], 403);
            // }

            $applications = $post->applications()->paginate(10);

            return response()->json([
                'data' => $applications,
                'message' => 'Applications retrieve successfully',
            ]);

        }catch(\Exception $e){
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }

    public function downloadAllResumes($postId)
    {
        try{
            $post = Post::findOrFail($postId);

            // if(Auth::id() !== $post->user_id)
            // {
            //     return response()->json(['error'=> 'Unauthorized'], 403);
            // }
            $applications = $post->applications()->get();

            $zip = new ZipArchive;
            $fileName = 'resumes_' . $postId . '.zip';
            $filePath = storage_path('app/public' . $fileName);
            if($zip->open($filePath, ZipArchive::CREATE) === true)
            {
                foreach($applications as $application){
                    if($application->resume_path)
                    {
                        $resumePath = storage_path('app/public/' . $application->resume_path);
                        $zip->addFile($resumePath, basename($application->resume_path));
                    }
                }
                $zip->close();
            }
            return response()->download($filePath)->deleteFileAfterSend(true);
         
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // public function downloadAllResumes($postId)
    // {
    //     try {
    //         $post = Post::findOrFail($postId);
            
    //         // Check if the authenticated user is the job poster
    //         if (Auth::id() !== $post->user_id) {
    //             return response()->json(['error' => 'Unauthorized'], 403);
    //         }

    //         $applications = $post->applications()->get();

    //         $zip = new ZipArchive;
    //         $fileName = 'resumes_' . $postId . '.zip';
            
    //         if ($zip->open(storage_path('app/public/' . $fileName), ZipArchive::CREATE) === TRUE) {
    //             foreach ($applications as $application) {
    //                 if ($application->resume_path) {
    //                     $resumePath = storage_path('app/public/' . $application->resume_path);
    //                     $zip->addFile($resumePath, basename($application->resume_path));
    //                 }
    //             }
    //             $zip->close();
    //         }

    //         return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend();
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
//     public function downloadAllResumes($postId)
// {
//     try {
//         $post = Post::findOrFail($postId);

//         // If user authorization is needed
//         // if (Auth::id() !== $post->user_id) {
//         //     return response()->json(['error' => 'Unauthorized'], 403);
//         // }

//         $applications = $post->applications()->get();

//         $zip = new ZipArchive;
//         $fileName = 'resumes_' . $postId . '.zip';
//         $filePath = storage_path('app/public/' . $fileName);

//         if ($zip->open($filePath, ZipArchive::CREATE) === true) {
//             foreach ($applications as $application) {
//                 if ($application->resume_path) {
//                     $resumePath = storage_path('app/public/' . $application->resume_path);
//                     if (file_exists($resumePath)) {
//                         $zip->addFile($resumePath, basename($application->resume_path));
//                     }
//                 }
//             }
//             $zip->close();
//         }

//         return response()->download($filePath)->deleteFileAfterSend(true);
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }


    
}







