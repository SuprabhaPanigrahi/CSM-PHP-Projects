<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'description',
        'start_date',
        'end_date',
        'approved'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved' => 'boolean'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}