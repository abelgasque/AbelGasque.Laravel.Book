<?php

namespace Tests\Feature;

use App\Models\Book;

use Tests\TestCase;

class BookTest extends TestCase
{
    public function test_insert_books()
    {
        $book = Book::factory()->count(5)->withAuthor()->create();
        $this->assertEquals(5, Book::count());
    }
}
