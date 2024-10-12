<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function editPost(Post $post, Request $request) {
        $incomingFields = request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $post->update($incomingFields);
        return redirect('/post/'. $post->id);

    }
    public function showEditForm(Post $post) {
        return view('update-post', ['post' => $post]);

    }
    public function delete(Post $post) {
        // if(auth()->user()->cannot('delete', $post)) {
        //     return 'You cannot do that';
        // }
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'Post Successfully Deleted');
    }
    public function showCreateForm() {
        return view('create-post');
    }
    public function storeNewPost(Request $request) {
        $incomingFields = request()->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with('success','Added a new Post.');
    }
    public function viewSinglePost(Post $post) {
        return view('single-post', ['post' => $post]);
    }
}
