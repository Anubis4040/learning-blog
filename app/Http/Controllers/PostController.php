<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('file')->get();

        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string|max:255',
            'user_id' => 'required',
            'category_id' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        $post = Post::create($validator->validated());

        $file = $request->file('image');

        $path = Storage::disk('public')->put('', $file);

        $post->file()->create([
            "path" => $path
        ]);

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if(!$post){
            return response("Post not found");
        }

        return response()->json($post); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string|max:255',
            'user_id' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        $post = Post::find($id);

        if(!$post){
            return response("Post not found");
        }

        $post->update($validator->validated());

        $post->save();

        if($file = $request->file('image')){

            Storage::disk('public')->delete($post->file->path);
            $post->file->delete();

            $path = Storage::disk('public')->put('', $file);

            $post->file()->create([
                "path" => $path
            ]);

        }

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $file = $post->file;

        if(!$post){
            return response("Post not found");
        }

        if ($post->delete()) {

            Storage::disk('public')->delete($file->path);
            $file->delete();

            return response()->json($post);
        } else {
            return response("Failed deletion",500);
        }
    }
}
