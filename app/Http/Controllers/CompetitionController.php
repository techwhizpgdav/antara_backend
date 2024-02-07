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
        $data = Competition::with(['category', 'society'])->get();

        return new CompetitionResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'society_id' => 'required|integer|exists:societies,id',
            'title' => 'required|string|max:200|unique:competitions',
            'image_url' => 'required|url',
            'rules' => 'required|json',
            'queries_to' => 'required|json',
        ]);

        $data = Competition::create($request->only(['category_id', 'society_id', 'title', 'image_url', 'rules', 'queries_to']));

        return new CompetitionResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Competition::with(['category', 'society'])->findOrFail($id);
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
            'title' => 'required|string|max:200|unique:competitions,title,' . $id . ',id',
            'image_url' => 'required|url',
            'rules' => 'required|json',
            'queries_to' => 'required|json',
        ]);

        $record = Competition::findOrFail($id);
        $update = $record->update($request->only(['category_id', 'society_id', 'title', 'image_url', 'rules', 'queries_to']));

        return response()->json(['data' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Competition::findOrFail($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete]);
    }
}
