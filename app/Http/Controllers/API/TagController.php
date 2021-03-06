<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleResponse(TagResource::collection(Tag::all()), 200);
        //return Tag::all();
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

        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->save();

        return $this->handleResponse(new TagResource($tag), 201);
        // return $tag;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);

            return $this->handleError('Tag not found', 404);
        }
        // $response = [
        //     'tag'    => $tag,
        // ];
        // return response($response, 200);
        return $this->handleResponse(new TagResource($tag), 200);
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
        ]);

        $tag = Tag::find($id);
        if (!$tag) {
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Tag not found', 404);
        }
        $tag->name = $request->name;
        $tag->save();
        // $response = [
        //     'tag'    => $tag,
        // ];
        // return response($response, 200);
        return $this->handleResponse(new TagResource($tag), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        $tag = Tag::find($id);
        if (!$tag) {
            // return response()->json([
            //     'message' => 'Record not found'
            // ], 404);
            return $this->handleError('Tag not found', 404);
        }
        $tag->delete();

        // return response()->json([
        //     'message' => 'Record deleted'
        // ], 200);
        return $this->handleResponse('Record deleted', 200);
    }
}
