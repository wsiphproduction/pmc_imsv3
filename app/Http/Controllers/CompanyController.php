<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Company;
// use App\Http\Controllers\Posts;
class CompanyController extends Controller {
  

    public function home() {
        $company = Company::all();
        return view("logistics.clearing.home", compact("company"));
    }

    public function create() {
        return view('logistics.clearing.create');
    }

   
    public function store(Request $request) {

        $validator = $request->validate([
            "broker_agent" => "required",
            // "Category" => "required",
            // "Description" => "required"
        ]);
        $broker_agent = $request->broker_agent;

        $slug = str_replace(" ", "-", strtolower($broker_agent));
        $slug = preg_replace("[/A-Za-z0-9/]", "-", $slug);
        $slug = preg_replace("[/*&%/]", "-", $slug);

        $postData = array(
            "broker_agent" => $broker_agent,
            "slug" => $slug,
            // "Category" => $request->Category,
            // "Description" => $request->Description,
        );

        $company = Company::create($postData);

        // dd($postData);
        if(!is_null($company)) {
            return back()->with("success", "Post published successfully");
        }

        else {
            return back()->with("error", "Whoops! failed to publish the post");
        }
    }

 
    public function show($id) {
        
        $company = Company::find($id);
        return view("logistics.clearing.show", compact("company"));
    }

 
    public function edit($id) {

        $company = Company::find($id);
        return view("logistics.clearing.edit", compact("company", "id"));
    }

   
    public function update(Request $request, $id) {

        $validator = $request->validate([
            "broker_agent"  => "required",
            // "Category" => "required",
            // "Description" => "required"
        ]);

        $broker_agent = $request->broker_agent;
        $slug = str_replace(" ", "-", strtolower($broker_agent));
        $slug = preg_replace("[/A-Za-z0-9/]", "-", $slug);
        $slug = preg_replace("[/*&%/]", "-", $slug);

        $postData = array(
            "broker_agent" => $broker_agent,
            "slug" => $slug,
            // "Category" => $request->category,
            // "Description" => $request->description
        );

        $post = Company::find($id)->update($postData);
        if($post == 1) {
            return back()->with("success", "Agent updated successfully");
        }
        else {
            return back()->with("failed", "Whoops! Failed to update");
        }
    }


    public function destroy($id) {
        $company = Company::find($id)->delete();
        if($company == 1) {
            return back()->with("success", "Agent deleted successfully");
        }
        else{
            return back()->with("failed", "Failed to delete post");
        }
    }

    public function agent_paginate($parameters = null)
    {
    $parameters = [];
    $o = Company::whereNotNull('id');
    $companies = Company::paginate(5);
    $o->orderBy('id');
    $o = $o->paginate(5);
  
    return view('logistics.clearing.home', compact('o', 'parameters', 'companies'));
  }

}