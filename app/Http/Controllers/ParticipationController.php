<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\User;
use App\Models\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResource
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions',
            'team_code' => 'nullable|exists:competition_user,team_code',
        ]);

        $user = User::find($request->user()->id);
        $competition = Competition::find($request->competition_id);

        $data = $competition->attach($user, ['created_at' => now(), 'updated_at' => now(), 'team_code' => Str::random(6)]);

        return new GeneralResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
