<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'image_path',
        'gender',
        'interests',
        'message',
        'terms_accepted'
    ];

    protected $casts = [
        'interests' => 'array',
        'terms_accepted' => 'boolean'
    ];
}