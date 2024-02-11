<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Team::all();
        return new TeamResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100|unique:teams,name',
            'photo' => 'required|url',
            'position'=>'required|string|max:200',
            'mobile' => 'required|string|max:20',
            'role' => 'required|string|in:organizer,web_development',
            'linked_in' => 'nullable|url|max:200',
            'github' => 'nullable|url|max:200',
            'instagram' => 'nullable|url|max:200',
        ]);

        $data = Team::create($request->only(['name','photo','position','mobile','role','linked_in','github','instagram']));
        return new TeamResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $record = Team::findOrFail($id);
        return new TeamResource($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100|unique:teams,name',
            'photo' => 'required|url',
            'position'=>'required|string|max:200',
            'mobile' => 'required|string|max:20',
            'role' => 'required|string|in:organizer,web_development',
            'linked_in' => 'nullable|url|max:200',
            'github' => 'nullable|url|max:200',
            'instagram' => 'nullable|url|max:200',
        ]);

        $record = Team::findOrFail($id);
        $update = $record->update($request->only(['name','photo','position','mobile','role','linked_in','github','instagram']));

        return response()->json($update);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Team::findOrFail($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete]);
    }

    public function getTeamMembersByRole(string $role)
    {
        // Validate the role here if needed
        
        $teamMembers = Team::where('role', $role)->get();

        return response()->json($teamMembers);
    }
}
