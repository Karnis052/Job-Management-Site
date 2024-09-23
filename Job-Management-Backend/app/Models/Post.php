<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['type', 
                            'title', 
                            'description',
                            'salary', 
                            'location', 
                            'company_name', 
                            'company_description',
                            'company_contact_phone',
                            'company_contact_email', 
                            'user_id',
                        ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
    public $timestamps = false;

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
