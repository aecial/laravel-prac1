<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body','user_id'];

    // Function to cater relationship post belongs to this user
    public function user() {
        //look for the user_id foreign key in the id in user table
        return $this->belongsTo(User::class, 'user_id');
    }
}
