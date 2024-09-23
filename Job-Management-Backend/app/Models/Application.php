<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['firstName', 'lastName', 'email', 'phone', 'major', 'university', 'resume_path', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
}
