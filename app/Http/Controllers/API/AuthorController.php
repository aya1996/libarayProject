<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Traits\ImageTrait;

class AuthorController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleResponse(authorResource::collection(Author::all()), 200);
        // return authorResource::collection(Author::all());
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
            $name = $this->saveImage($request->image);
            $author->image = $name;
            // $image = $request->file('image');
            // $name = mt_rand() . '.' . $image->getClientOriginalExtension();

            // $image->move(public_path() . '/images/', $name);

        }


        $author->save();

        return $this->handleResponse(new AuthorResource($author), 201);
        // return new AuthorResource($author);
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
            return $this->handleError('Author not found', 404);
        }

        return $this->handleResponse(new AuthorResource($author), 200);
        // return new AuthorResource($author);
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
            $name = $this->updateImage($request->image);
            $author->image = $name;
            // $image = $request->file('image');
            // $name = mt_rand() . '.' . $image->getClientOriginalExtension();
            // $image->move(public_path() . '/images/', $name);
        }


        $author->update();
        //  dd($author);

        // $response = [
        //     'message' => 'Author updated successfully',

        // ];
        return $this->handleResponse(new AuthorResource($author), 200);
        // return new AuthorResource($author, $response);
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
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Author not found', 404);
        }
        $author->delete();
        File::delete(public_path('images/' . $author->image));
        // $response = [
        //     'message'    => 'Record deleted successfully'
        // ];
        // return response($response, 200);
        return $this->handleResponse('Author deleted successfully', 200);
    }

    public function showProfile($id)
    {

        $author = Author::find($id);

        if (!$author) {
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Author not found', 404);
        }

        $author->books = $author->books()->get();


        return $this->handleResponse(new AuthorResource($author), 200);
        //return new AuthorResource($author);
    }
}
