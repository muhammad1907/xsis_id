<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class MoviesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::all();
        return response()->json($movies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|unique:movies|max:255',
            'description' => 'nullable',
            'rating' => 'nullable|numeric|between:0,10',
            'image' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }

        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->rating = $request->rating;
        $movie->image = $request->image;
        $movie->created_at = Carbon::now();
        $movie->updated_at = null;
        $movie->save();

        return response()->json([
            'message' => 'Movie created successfully',
            'data' => $movie,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $movie = Movie::findOrFail($id);

        $validatedData = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => [
                'required',
                Rule::unique('movies')->ignore($movie->id),
                'max:255',
            ],
            'description' => 'nullable',
            'rating' => 'nullable|numeric|between:0,10',
            'image' => 'nullable|url|max:255',
        ]);

        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->rating = $request->rating;
        $movie->image = $request->image;
        $movie->updated_at = Carbon::now();
        $movie->save();

        return response()->json([
            'message' => 'Movie updated successfully',
            'data' => $movie,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return response()->json([
            'message' => 'Movie deleted successfully',
            'data' => $movie,
        ]);
    }
}
