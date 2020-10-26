<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public $success_status = 200;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts          =           array();
        $user           =           Auth::user();

        if(!is_null($user)) {
            $posts      =           Post::where("user_id", $user->id)->get();
            if(count($posts) > 0) {
                return response()->json(["status" => $this->success_status, "success" => true, "count" => count($posts), "data" => $posts]);
            }
    
            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no post found"]);
            }
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! invalid auth token"]);
        }      
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user           =           Auth::user();

        if(!is_null($user)) { 
            $validator      =           Validator::make($request->all(),
                [
                    "title"         =>      "required",
                    "description"   =>      "required",
                ]
            );

            if($validator->fails()) {
                return response()->json(["validation_errors" => $validator->errors()]);
            }

            $post_array         =       array(
                "title"         =>      $request->title,
                "description"   =>      $request->description,
                "user_id"       =>      $user->id
            );

            $post               =       Post::create($post_array);

            if(!is_null($post)) {
                return response()->json(["status" => $this->success_status, "success" => true, "data" => $post]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! post not created."]);
            }
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! invalid auth token"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user       =           Auth::user();

        if(!is_null($user)) {
            $post       =           Post::where("id", $id)->where("user_id", $user->id)->first();

            if(!is_null($post)) {
                return response()->json(["status" => $this->success_status, "success" => true, "data" => $post]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! no post found"]);
            }
        }

        else {
            return response()->json(["status" => "failed", "message" => "Whoops! invalid auth token"]);
        }   
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $input          =           $request->all();
        $user           =           Auth::user();

        if(!is_null($user)) {

            // validation
            $validator      =       Validator::make($request->all(), [
                "title"           =>      "required",
                "description"     =>      "required",
            ]);

            if($validator->fails()) {
                return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
            }

            // update post
            $update       =           $post->update($request->all());

            return response()->json(["status" => $this->success_status, "success" => true, "data" => $post]);

        }
        else {
            return response()->json(["status" => "failed", "message" => "Whoops! invalid auth token"]);
        }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if(!is_null($post)) {
            $delete         =       $post->delete();

            if($delete == true) {
                return response()->json(["status" => $this->success_status, "success" => true, "message" => "Success! post deleted"]);
            }

            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Alert! post not deleted"]);
            }  
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Alert! post not found"]);
        }
    }
}
