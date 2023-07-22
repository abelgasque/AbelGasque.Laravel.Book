<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name', 'description', 'price', 'idAuthor'];

    public function author()
    {
        return $this->belongsTo(Author::class, 'idAuthor');
    }
}
