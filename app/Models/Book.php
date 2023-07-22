<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'idAuthor'];

    public function author()
    {
        return $this->belongsTo(Author::class, 'idAuthor');
    }

    public function getCrateNotification()
    {
        return "Create new book: {$this->id}";
    }

    public function getUpdateNotification()
    {
        return "Update new book: {$this->id}";
    }
}
