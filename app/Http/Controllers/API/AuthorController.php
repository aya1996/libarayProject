<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Author::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthorRequest  $request)
    {
        $author = new Author();
        $author->name = $request->name;
        $author->title = $request->title;
        $author->save();

        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $name = mt_rand() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path() . '/images/', $name);
        }

        $author->image = $name;
        $author->save();

        $response = [
            'author'    => $author,

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

        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }

        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AuthorRequest $request, $id)
    {
        $author = Author::find($id);
        $author->name = $request->name;
        $author->title = $request->title;
        $author->update();

        if ($request->hasfile('image')) {
            File::delete(public_path('images/' . $author->image));

            $image = $request->file('image');
            $name = mt_rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path() . '/images/', $name);
        }

        $author->image = $name;
        $author->update();
        //  dd($author);

        $response = [
            'message' => 'Author updated successfully',

        ];

        return new AuthorResource($author, $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $author->delete();
        File::delete(public_path('images/' . $author->image));
        $response = [
            'message'    => 'Record deleted successfully'
        ];
        return response($response, 200);
    }

    public function showProfile($id)
    {

        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }

        $author->books = $author->books()->get();


        $response = [
            'author'    => $author,

        ];
        return response($response, 200);
    }
}
