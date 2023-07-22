<?php

namespace Tests\Unit;

use App\Models\Author;

use Tests\TestCase;

class AuthorTest extends TestCase
{    
    public function test_insert_authors()
    {
        Author::factory()->count(5)->create();
        $this->assertEquals(5, Author::count());
    }
}
