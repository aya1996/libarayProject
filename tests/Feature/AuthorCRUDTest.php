<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorCRUDTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    //use RefreshDatabase;

    public $author;

    public function setup(): void
    {
        parent::setup();

        //$this->product = Author::factory()->create();
    }
    public function test_get_all_authors()
    {
        $response = $this->get('api/authors');

        $response
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'name',
                    'title',
                    'image',
                    'created_at',
                    'updated_at',
                ],

            ]);
        $response->assertStatus(200);
    }

    public function test_get_author()
    {
        $author = Author::factory()->create();
        $response = $this->get('api/authors/' . $author->id);

        $response->assertStatus(200);
    }


    public function test_create_author()
    {
        $response = $this->post('api/author', [
            'name' => 'Test Author',
            'title' => 'test',
            'image' => 'test.jpg',
        ]);

        $response->assertSessionHasNoErrors();
        // $this->assertCount(1, Author::all());
        //  $response->assertStatus(201);
        $this->assertDatabaseHas('authors', [
            'name' => 'Test Author',
            'title' => 'test',
            'image' => 'test.jpg',
        ]);
    }

    public function test_update_author()
    {
        $author = Author::factory()->create();
        $response = $this->put('api/authors/' . $author->id, [
            'name' => 'Test Author',
            'title' => 'test',
            'image' => 'test.jpg',
        ]);


        $response->assertSessionHasNoErrors();
        // $this->assertCount(1, Author::all());
        // $response->assertStatus(201);

        $this->assertDatabaseHas('authors', [
            'name' => 'Test Author',
            'title' => 'test',
            'image' => 'test.jpg',
        ]);
    }

    public function test_delete_author()
    {
        $author = Author::factory()->create();
        //    $this->assertEquals(1, Author::count());
        $response = $this->delete('api/authors/' . $author->id);

        //  dd($response->json());
        $response->assertStatus(200);
        //   $response->assertStatus(200);
        // $this->assertEquals(0, Author::count());
    }

    public function test_get_author_profile()
    {


        $author = Author::factory()->create();
        $response = $this->get('api/author/showProfile/' . $author->id);


        $response->assertStatus(200);
    }
}
