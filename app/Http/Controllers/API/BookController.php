<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string|max:855',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|exists:authors,id',
            'tags' => 'required|array|exists:tags,id',
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->save();

        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $name =  mt_rand() . $image->getClientOriginalName();

            $image->move(public_path() . '/images/', $name);
        }

        $book->image = $name;
        $book->save();

        $book->tags()->attach($request->tags);

        $response = [
            'book'    => $book,

        ];
        return response($response, 201);
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
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'title' => 'required|string|max:855',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|exists:authors,id'
        ]);

        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $book->update([
            'title' => $request->title,
            'image' => $request->image,
            'author_id' => $request->author_id,
        ]);
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
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $book->delete();
        $response = [
            'book'    => $book,

        ];
        return response($response, 200);
    }

    public function showProfile($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $book->author = $book->author;
        $book->tags = $book->tags;
        $response = [
            'book'    => $book,

        ];
        return response($response, 200);
    }
}
