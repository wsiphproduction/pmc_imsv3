<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Post;
// use App\Http\Controllers\Posts;
class PostController extends Controller {
  

    public function index() {
        $posts = Post::all();
        return view("logistics.index", compact("posts"));
    }

    public function create() {
        return view('logistics.create-post');
    }

   
    public function store(Request $request) {

        $validator = $request->validate([
            "Portname" => "required",
            // "Category" => "required",
            // "Description" => "required"
        ]);
        $Portname = $request->Portname;

        $slug = str_replace(" ", "-", strtolower($Portname));
        $slug = preg_replace("[/A-Za-z0-9/]", "-", $slug);
        $slug = preg_replace("[/*&%/]", "-", $slug);

        $postData = array(
            "Portname" => $Portname,
            "slug" => $slug,
            // "Category" => $request->Category,
            // "Description" => $request->Description,
        );

        $post = Post::create($postData);

        if(!is_null($post)) {
            return back()->with("success", "Post published successfully");
        }

        else {
            return back()->with("error", "Whoops! failed to publish the post");
        }
    }

 
    public function show($id) {
        $post = Post::find($id);
        return view("logistics.show-post", compact("post"));
    }

 
    public function edit($id) {
        $post = Post::find($id);
        return view("logistics.edit-post", compact("post"));
    }

   
    public function update(Request $request, $id) {
        $validator = $request->validate([
            "Portname"  => "required",
            // "Category" => "required",
            // "Description" => "required"
        ]);

        $Portname = $request->Portname;
        $slug = str_replace(" ", "-", strtolower($Portname));
        $slug = preg_replace("[/A-Za-z0-9/]", "-", $slug);
        $slug = preg_replace("[/*&%/]", "-", $slug);

        $postData = array(
            "Portname" => $Portname,
            "slug" => $slug,
            // "Category" => $request->category,
            // "Description" => $request->description
        );

        $post = Post::find($id)->update($postData);
        if($post == 1) {
            return back()->with("success", "Post updated successfully");
        }
        else {
            return back()->with("failed", "Whoops! Failed to update");
        }
    }


    public function destroy($id) {
        $post = Post::find($id)->delete();
        if($post == 1) {
            return back()->with("success", "Post deleted successfully");
        }
        else{
            return back()->with("failed", "Failed to delete post");
        }
    }
}