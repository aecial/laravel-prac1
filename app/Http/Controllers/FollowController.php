<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //
    public function createFollow(User $user) {
        // No Follow Self
        if($user->id == auth()->user()->id) {
            return back()->with('failure', 'You cannot follow yourself');
        }
        // No follow followed already
        // Check if user_id that is currently logged in has a record that follows the $user->id
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if($existCheck) {
            return back()->with('failure', 'You are already following this user');
        }
        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();
        return 'Omkei!';
    }
    public function removeFollow(User $user) {
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
    }
}
