<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Posts retrieved successfully.',
            'data' => Post::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
         try{
             $validatedData = $request->validated();
                // dd($validatedData['title']);
            $post = Post::create([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'user_id' => $validatedData['user_id'],
                'status' => $validatedData['status']
            ]);

            return response()->json([
                'message' => 'create succely'
            ]);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Faile create user',
                'error' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
