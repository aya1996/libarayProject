<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\BookTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Traits\ImageTrait;

class BookController extends Controller
{
    use ImageTrait;
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

        return $this->handleResponse(BookResource::collection($bookauthor), 200);
        //return new bookResource($bookauthor);

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
            $name = $this->saveImage($request->image);
            $book->image = $name;
            // $image = $request->file('image');
            // $name =  mt_rand() . '.' . $image->getClientOriginalExtension();

            // $image->move(public_path() . '/images/', $name);
        }


        $book->save();

        $book->tags()->attach($request->tags);

        return $this->handleResponse(new BookResource($book), 'Book created successfully', 201);
        //     $response = [
        //         'message'    => 'Book created successfully',

        //     ];
        //     return new BookResource($book, $response);
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
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Book not found', 404);
        };

        return $this->handleResponse(new BookResource($book), 200);
        //  return new bookResource($book, 200);
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
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Book not found', 404);
        }

        $book->title = $request->title;
        $book->author_id = $request->author_id;
        $book->update();

        if ($request->hasfile('image')) {
            File::delete(public_path('images/' . $book->image));
            $name = $this->updateImage($request->image);
            $book->image = $name;
            // $image = $request->file('image');
            // $name =  mt_rand() . '.' . $image->getClientOriginalExtension();

            // $image->move(public_path() . '/images/', $name);
        }


        $book->update();

        $book->tags()->sync($request->tags);

        // $response = [
        //     'message'    => 'book updated successfully',

        // ];
        // return new bookResource($book, $response);
        return $this->handleResponse(new BookResource($book), 'Book updated successfully', 200);
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
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Book not found', 404);
        }
        $book->delete();
        File::delete(public_path('images/' . $book->image));
        // return response()->json([
        //     'message' => 'Record deleted'
        // ], 200);
        return $this->handleResponse(null, 'Book deleted successfully', 200);
    }

    public function showProfile($id)
    {
        $book = Book::find($id);
        if (!$book) {
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Book not found', 404);
        }

        $book->auther()->tags()->get();

        return $this->handleResponse(new BookResource($book), 200);
        // return new bookResource($book, 200);
    }
}
