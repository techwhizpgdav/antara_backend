<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompetitionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $competitions = Competition::when($request->input('category_id'), function ($query) use ($request) {
            $query->where('category_id', $request->input('category_id'));
        })
        ->when($request->input('society_id'), function ($query) use ($request) {
            $query->where('society_id', $request->input('society_id'));
        })
        ->get();

       
        $resources =CompetitionResource::collection($competitions);

        return response()->json(['data' => $resources], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'society_id' => 'required|integer|exists:societies,id',
            'title' => 'required|string|max:200',
            'image_url' => 'required|url',
        ]);

        $data = Competition::create($request->only(['category_id','society_id','title','image_url']));

        $resource = new CompetitionResource($data);

        return response()->json(['data' => $resource], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $record = Competition::find($id);
        return new CompetitionResource($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'society_id' => 'required|integer|exists:societies,id',
            'title' => 'required|string|max:200',
            'image_url' => 'required|url',
        ]);

        $record = Competition::find($id);
        $update = $record->update($request->only(['category_id', 'society_id', 'title', 'image_url']));

        return response()->json(['data' => new CompetitionResource($update)], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Competition::find($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete], 200);
    }
}
