<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookCRUDTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_get_all_books()
    {
        $response = $this->get('api/books');
        $response
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'image',
                    'author_id',
                    'tags' => [
                        '*' => [
                            'id',
                            'name',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                ],

            ]);

        $response->assertStatus(200);
    }

    public function test_get_book()
    {
        $book = Book::factory()->create();
        $response = $this->get('api/books/' . $book->id);

       

        $response->assertStatus(200);
    }

    public function test_create_book()
    {

        $response = $this->post('api/book', [
            'title' => 'Test Book',
            'image' => 'test.jpg',
            'author_id' => 1,
            'tags' => [1, 2],
        ]);


        $response->assertSessionHasNoErrors();
        //$this->assertCount(1, Book::all());
        //  $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'image' => 'test.jpg',
            'author_id' => 1,

        ]);

        // dd($response->json());
    }

    public function test_update_book()
    {
        $book = Book::factory()->create();
        $response = $this->put('api/books/' . $book->id, [
            'title' => 'Test Book',
            'image' => 'test.jpg',
            'author_id' => 1,
            'tags' => [1, 2],
        ]);

        $response->assertSessionHasNoErrors();
        // $this->assertCount(1, Book::all());
        //  $response->assertStatus(201);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'image' => 'test.jpg',
            'author_id' => 1,

        ]);
    }

    public function test_delete_book()
    {
        $book = Book::factory()->create();
        //    $this->assertEquals(1, Book::count());
        $response = $this->delete('api/books/' . $book->id);

        //  dd($response->json());
        $response->assertStatus(200);
        //   $response->assertStatus(200);
        // $this->assertEquals(0, Book::count());
    }
}
