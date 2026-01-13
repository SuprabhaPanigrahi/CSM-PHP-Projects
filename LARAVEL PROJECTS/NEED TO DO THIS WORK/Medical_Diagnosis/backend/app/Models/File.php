<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'visit_id',
        'original_file_name',
        'stored_file_name',
        'file_path',
        'file_size',
        'file_type',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
