<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagCRUDTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_get_all_tags()
    {
        $response = $this->get('api/tags');
        //   dd($response->json());
        $response->assertStatus(200);
    }

    public function test_get_tag()
    {
        $tag = Tag::factory()->create();
        $response = $this->get('api/tags/' . $tag->id);

        $response->assertStatus(200);
    }

    public function test_create_tag()
    {
        $response = $this->post('api/tag', [
            'name' => 'Test Tag',

        ]);

        $response->assertSessionHasNoErrors();
        // $this->assertCount(1, Tag::all());

        $this->assertDatabaseHas('tags', [
            'name' => 'Test Tag',

        ]);
        $response->assertStatus(200);
    }

    public function test_update_tag()
    {
        $tag = Tag::factory()->create();
        $response = $this->put('api/tags/' . $tag->id, [
            'name' => 'Test Tag',

        ]);

        $response->assertSessionHasNoErrors();
        // $this->assertCount(1, Tag::all());
        $response->assertStatus(200);
        $this->assertDatabaseHas('tags', [
            'name' => 'Test Tag',

        ]);
    }

    public function test_delete_tag()
    {
        $tag = Tag::factory()->create();
        $response = $this->delete('api/tags/' . $tag->id);

        $response->assertSessionHasNoErrors();
        // $this->assertCount(1, Tag::all());
        $response->assertStatus(200);
    }
}
