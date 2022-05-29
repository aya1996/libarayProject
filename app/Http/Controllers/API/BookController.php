<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\BookTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $bookauthor = Book::query()->when(request('author_id'), function ($query) {
            return $query->where('author_id', request('author_id'));
        })->when(request('tag_id'), function ($query) {
            return $query->whereHas('tags', function ($query) {
                return $query->where('tag_id', request('tag_id'));
            });
        })->with('author', 'tags')->get();

        return $bookauthor;

        //return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {

        $book = new Book();
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->save();

        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $name =  mt_rand() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path() . '/images/', $name);
        }

        $book->image = $name;
        $book->save();

        $book->tags()->attach($request->tags);

        $response = [
            'message'    => 'Book created successfully',

        ];
        return new BookResource($book, $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $response = [
            'book'    => $book,

        ];
        return response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, $id)
    {


        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }

        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->update();

        if ($request->hasfile('image')) {
            File::delete(public_path('images/' . $book->image));

            $image = $request->file('image');
            $name =  mt_rand() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path() . '/images/', $name);
        }

        $book->image = $name;
        $book->update();

        $book->tags()->sync($request->tags);

        $response = [
            'book'    => $book,

        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $book->delete();
        File::delete(public_path('images/' . $book->image));
        return response()->json([
            'message' => 'Record deleted'
        ], 200);
    }

    public function showProfile($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }

        $book->auther()->tags()->get();

        $response = [
            'book'    => $book,

        ];
        return response($response, 200);
    }
}
