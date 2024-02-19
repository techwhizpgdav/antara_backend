<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Team::all();
        return new GeneralResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'required|url',
            'position' => 'required|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'role' => Team::roleRules(),
            'linked_in' => 'nullable|url|max:200',
            'github' => 'nullable|url|max:200',
            'instagram' => 'nullable|url|max:200',
        ]);

        $data = Team::create($request->only(['name','photo','position','mobile','role','linked_in','github','instagram']));
        
        return new GeneralResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $record = Team::findOrFail($id);
        return new GeneralResource($record);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'required|url',
            'position' => 'required|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'role' => Team::roleRules(),
            'linked_in' => 'nullable|url|max:200',
            'github' => 'nullable|url|max:200',
            'instagram' => 'nullable|url|max:200',
        ]);

        $record = Team::findOrFail($id);
        $update = $record->update($request->only(['name','photo','position','mobile','role','linked_in','github','instagram']));
        return response()->json(['data' => $update], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Team::findOrFail($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete], 200);
    }
}
