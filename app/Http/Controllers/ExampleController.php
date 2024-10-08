<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        $name = 'Ted';
        $age = 23;
        $persons = ['Ted', 'Maya', 'Denmark'];
        return view('homepage', ['persons' => $persons,'name' => $name, 'age' => $age]);
    }
    public function aboutpage() {
        return view('single-post');
    }
}
