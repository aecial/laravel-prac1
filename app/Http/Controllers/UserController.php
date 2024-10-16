<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    public function uploadAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:2000'
        ]);
        // get the current user
        $user = auth()->user();
        // userid-uniqueId.jpg
        $filename = $user->id . "-" . uniqid() . ".jpg";
        // import Image Manager with GD Driver
        $manager = new ImageManager(new Driver());
        // Let the manager read the image
        $image = $manager->read($request->file('avatar'));
        // object-cover the image and convert to jpeg
        $imgData = $image->cover(120,120)->toJpeg();
        // Store in public/avatars
        Storage::put('public/avatars/'. $filename, $imgData);
        $oldAvatar = $user->avatar;
        $user->avatar = $filename;
        $user->save();
        // if the old avatar is not the fallback image
        if($oldAvatar != '/fallback-avatar.jpg') {
            // delete the old image
            Storage::delete(str_replace('/storage/', 'public/', $oldAvatar));
        }
        return redirect('/profile/'. $user->username)->with('success', 'Congrats on the New Avatar');
    }
    public function showAvatarForm() {
        return view('avatar-form');
    }
    public function profile(User $user) {
        $currentlyFollowing = 0;
        if(auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }
        
        return view('profile-posts', ['currentlyFollowing' => $currentlyFollowing ,'username' => $user->username, 'posts' => $user->posts()->latest()->get(), 'postCount' => $user->posts()->count(), 'avatar' => $user->avatar]);
    }
    public function showCorrectHomepage() {
        if (auth()->check()) {
           return view('homepage-feed');
        } else {
            return view('homepage');
        }
        
    }
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => ['required','min:3', 'max:20', Rule::unique('users','username')],
            'email' => ['required', 'email', Rule::unique('users','email')],
            'password' => ['required', 'min:6', 'confirmed']
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'Thank you for creating an account.');
    }
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);
        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have logged in.');
        } else {
            return redirect('/')->with('failure', 'Invalid Credentials.');
        }
        
    }
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'You have logged out.');
    }
}
