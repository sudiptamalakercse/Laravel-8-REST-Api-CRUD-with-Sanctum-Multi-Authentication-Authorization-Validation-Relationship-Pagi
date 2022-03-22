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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'fees' => 'required',
        ]);

        $admin=auth('admin')->user();

        return $admin->posts()->create($request->all());

       // return Post::create($request->all());
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
            $final_post['admin_id']=$post->admin->id;
            $final_post['admin_name']=$post->admin->name;
            $final_post['admin_email']=$post->admin->email;
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
        $request->validate([
            'name' => 'required',
            'city' => 'required',
            'fees' => 'required',
        ]);

        $post = Post::find($id);

        $post->update($request->all());
        
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Post::find($id)->delete();
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
            $current_post['admin_id']=$post->admin->id;
            $current_post['admin_name']=$post->admin->name;
            $current_post['admin_email']=$post->admin->email;
            $current_post['created_at']=$post->created_at;
            $current_post['updated_at']=$post->updated_at;
            
            array_push($final_posts,$current_post);

        });
                
        return $final_posts;
     }
}
