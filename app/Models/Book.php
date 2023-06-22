<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'book_name',
        'category_id',
        'slug',
        'created_user',
        'updated_user',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_user');
    }

    public function category()
    {
        return $this->belongsTo(Category::class ,'category_id');
    }

}
