<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksTest extends TestCase
{

    use RefreshDatabase;

    public function test_can_get_all_books()
    {
        $books = Book::factory(5)->create();

        $this->getJson(route("books.index"))
            ->assertJsonFragment(
            [
                "title" => $books[0]->title
            ]
            )
            ->assertJsonFragment(
            [
                "title" => $books[1]->title
            ]
            );
    }

    public function test_can_get_one_book()
    {
        $book = Book::factory()->create();
        $this->getJson(route("books.show", $book))
            ->assertJsonFragment(
            [
                "title" => $book->title
            ]
        );
    }

    public function test_can_create_books()
    {
        $this->postJson(route("books.store"), [])->assertJsonValidationErrorFor("title");

        $this->postJson(route("books.store"), [
            "title" => "My new Book"
        ])->assertJsonFragment([
            "title" => "My new Book"
        ]);

        $this->assertDatabaseHas("books", [
            "title" => "My new Book"
        ]);
    }

    public function test_can_update_books()
    {
        $book = Book::factory()->create();

        $this->patchJson(route("books.update", $book), [])->assertJsonValidationErrorFor("title");

        $this->patchJson(route("books.update", $book), [
            "title" => "Update Book"
        ])->assertJsonFragment([
            "title" => "Update Book"
        ]);

        $this->assertDatabaseHas("books", [
            "title" => "Update Book"
        ]);
    }

    public function test_can_delete_books()
    {
        $book = Book::factory()->create();


        $this->deleteJson(route("books.destroy", $book))->assertNoContent();
        $this->assertDatabaseCount("books", 0);
    }
}
