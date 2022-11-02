<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;


class ApiBooksTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
   /* public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    use RefreshDatabase;
    /** @test */
    function can_get_all_books(){

        $books = Book::factory(4) -> create();
        $response = $this -> getJson(route('books.index'))/*->dump()*/;

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ])->assertJsonFragment([
            'title' => $books[1]->title
        ]);
        
        //dd($book);

    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory() -> create();
        $response = $this -> getJson(route('books.show',$book));
        $response->assertJsonFragment([
            'title' => $book->title
        ]);
    }

    /** @test */
    function can_create_books(){
        
        $this-> postJson(route('books.store',[]))
        ->assertJsonValidationErrorFor('title');

        $this -> postJson(route('books.store', [
            'title' => 'PostTest'
        ]))->assertJsonFragment([
            'title' => 'PostTest'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'PostTest'
        ]);
    }

    /** @test */
    function can_update_books(){

        $book = Book::factory() -> create();

        $this-> patchJson(route('books.update', $book),[])
        ->assertJsonValidationErrorFor('title');

        $response = $this -> patchJson(route('books.update', $book), 
        [
            'title' => 'UpdateTest'
        ]);
        $response->assertJsonFragment([
            'title' => 'UpdateTest'
        ]);

        $this->assertDatabaseHas('books',[
            'title' => 'UpdateTest'
        ]);
    }


    /** @test */
    function can_delate_books(){

        $book = Book::factory() -> create();

        $this-> deleteJson(route('books.destroy', $book))
        ->assertNoContent();

        $this -> assertDatabaseCount('books',0);
    }


}
