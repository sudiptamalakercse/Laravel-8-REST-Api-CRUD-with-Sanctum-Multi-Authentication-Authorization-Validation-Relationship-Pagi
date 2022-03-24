<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $posts=Post::all();
        $final_posts=$this->modify_posts_by_admin_relationship($posts);
        return $final_posts;
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

    public function store(Request $request)
    {
      //Authorization Check

        if($request->user()->cannot('create_post_by_admin',Post::class)) {
        
        return response([
            'message' => 'Unauthorized!!',
        ], 403); 

        }
       
       // End Authorization Check 

       $request->validate([
            'name' => 'required',
            'city' => 'required',
            'fees' => 'required',
        ]);

        $admin=auth('admin')->user();

        return $admin->posts()->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post=Post::find($id);

        $final_post=[];
            
            $final_post['id']=$post->id;
            $final_post['name']=$post->name;
            $final_post['city']=$post->city;
            $final_post['fees']=$post->fees;
            $final_post['admin']=$post->admin;
            $final_post['created_at']=$post->created_at;
            $final_post['updated_at']=$post->updated_at;
        
        return $final_post;
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

      //  $this->authorize('update_by_admin', $post);

        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'fees' => 'required',
        ]);

        $post = Post::find($id);
        
        //Authorization Check

        if($request->user()->cannot('update_by_admin',$post)) {
        
        return response([
            'message' => 'Unauthorized!!',
        ], 403); 

        }
        
        // End Authorization Check 

        $post->update($request->all());
        
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        $post=Post::find($id);

        if($post){
        
        //Authorization Check

        if($request->user()->cannot('delete_by_admin',$post)) {
        
        return response([
            'message' => 'Unauthorized!!',
        ], 403); 

        }
        
        // End Authorization Check 

        $is_delete=$post->delete();

        if($is_delete){
        
        return response([
            'message' => 'The Post is Deleted Successfully!!',
        ], 200); 

        }
        }
        else{

        return response([
            'message' => 'The Post Does Not Exist!!',
        ], 401); 

        }
    }

    /**
     * Search based on City.
     *
     * @param  int  $city
     * @return \Illuminate\Http\Response
     */
    public function search($city)
    {
        $posts=Post::where('city', $city)->get();
        $final_posts=$this->modify_posts_by_admin_relationship($posts);
        return $final_posts;
    }

     private function modify_posts_by_admin_relationship($posts)
     {
        $final_posts=[];

        $posts->each(function ($post) use (&$final_posts) {
          
            $current_post=[];
            
            $current_post['id']=$post->id;
            $current_post['name']=$post->name;
            $current_post['city']=$post->city;
            $current_post['fees']=$post->fees;
            $current_post['admin']=$post->admin;
            $current_post['created_at']=$post->created_at;
            $current_post['updated_at']=$post->updated_at;
            
            array_push($final_posts,$current_post);

        });
                
        return $final_posts;
     }//end


    public function delete_selected_post(Request $request)
    {
       
        $post_ids=$request->post_ids;
         
        //Authorization Check

        $admin=$request->user();

        foreach ($post_ids as $post_id){
       
         $post=Post::find($post_id);

         if($admin->cannot('delete_by_admin', $post)) {
            
             return response([
            'message' => 'Unauthorized!!',
             ], 403); 

          }
      
         }

         //End Authorization Check
 
      if(is_array($post_ids) && count($post_ids)>0){
        
        Post::destroy($post_ids);

        return response([
            'message' => 'Selected Posts are Deleted Successfully!!',
        ], 200); 
    
     }
     else
     {
        return response([
            'message' => 'Please Select the Posts to Delete!!',
        ], 401); 
     }
       
    }//end
}
