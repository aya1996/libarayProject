<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

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
    public function store(Request $request)
    {

        $this->validate($request, [

            'name' => 'required|string|max:255',
            'title' => 'required|string|max:855',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        $author =  Author::create([
            'name' => $request->name,
            'title' => $request->title,
            'image' => $request->image,


        ]);
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
        $response = [
            'author'    => $author,

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
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:855',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $author->update($request->all());
        $response = [
            'author'    => $author,

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
        $author = Author::find($id);
        if (!$author) {
            return response()->json([
                'message' => 'Record not found'
            ], 404);
        }
        $author->delete();
        $response = [
            'message'    => 'Record deleted successfully'
        ];
        return response($response, 200);
    }
}
