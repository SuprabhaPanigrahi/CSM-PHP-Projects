<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'test_name'];

    public function category()
    {

        return $this->belongsTo(Category::class);
    }

    public function visits()
    {

        return $this->belongsToMany(
            Visit::class,
            'visit_tests',
            'test_id',
            'visit_id'
        );
    }
}
