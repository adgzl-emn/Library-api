<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = 'BookActivityLogs';

    protected $fillable = [
        'book_id',
        'visitory_id',
        'returned_at',
    ];

   public function getBook(){
       return $this->belongsTo(Book::class , 'book_id');
   }

    public function getVisitory(){
        return $this->belongsTo(Visitors::class , 'visitory_id');
    }


}
