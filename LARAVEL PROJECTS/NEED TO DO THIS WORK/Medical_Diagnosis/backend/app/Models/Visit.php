<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test;

class Visit extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'visit_date', 'notes', 'created_by'];

    public function patient()
    {

        return $this->belongsTo(Patient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tests()
    {

        return $this->belongsToMany(
            Test::class,
            'visit_tests',
            'visit_id',
            'test_id'
        );
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
