<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use App\Http\Resources\CompetitionResource;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Competition::with(['category','society'])->get();

        return new CompetitionResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories',
            'society_id' => 'required|integer|exists:societies',
            'title' => 'required|string|max:200',
            'image_url' => 'required|url',
        ]);

        $data = Competition::create($request->only(['category_id', 'society_id', 'title', 'image_url']));

        return new CompetitionResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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

        return new CompetitionResource($update);
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