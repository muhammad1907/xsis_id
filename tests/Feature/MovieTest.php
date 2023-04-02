<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Movie;


class MovieTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testMovieIndex()
    {
        $response = $this->get('/api/movies');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function testMovieStore()
    {
        $data = [
            'title' => 'Test Movie',
            'description' => 'This is a test movie',
            'rating' => 8.5,
            'image' => 'http://example.com/test.jpg',
            'updated_at' => null,
        ];
            
        $response = $this->post('/api/movies', $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Movie created successfully'])
            ->assertJsonFragment(['title' => 'Test Movie']);

        $this->assertDatabaseHas('movies', ['title' => 'Test Movie']);

        $movie = Movie::where('title', 'Test Movie')->first();
        $this->assertNotNull($movie);
        $this->assertEquals($data['description'], $movie->description);
        $this->assertEquals($data['rating'], $movie->rating);
        $this->assertEquals($data['image'], $movie->image);
        $this->assertEquals($data['updated_at'], $movie->updated_at);
    }

    public function testMovieUpdate()
    {
        $movie = factory(Movie::class)->create();
        $data = [
            'title' => 'Updated Movie',
            'description' => 'This is an updated movie',
            'rating' => 9.0,
            'image' => 'http://example.com/updated.jpg',
        ];

        $response = $this->put("/api/movies/{$movie->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Movie updated successfully'])
            ->assertJsonFragment(['title' => 'Updated Movie']);

        $updatedMovie = Movie::find($movie->id);
        $this->assertEquals($data['title'], $updatedMovie->title);
        $this->assertEquals($data['description'], $updatedMovie->description);
        $this->assertEquals($data['rating'], $updatedMovie->rating);
        $this->assertEquals($data['image'], $updatedMovie->image);
    }

    public function testMovieDelete()
    {
        $movie = factory(Movie::class)->create();

        $response = $this->delete("/api/movies/{$movie->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Movie deleted successfully']);

        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }
}
