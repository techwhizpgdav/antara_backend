<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Round;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\GeneralResource;

class RoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(1);
        $usersRounds = $user->societyCompetitions->flatMap(function ($competition) {
            return $competition->rounds;
        });
        return new GeneralResource($usersRounds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'competition_id' => ['required', 'exists:competitions,id'],
            'mode' => ['required', 'string', Rule::in(['online', 'offline'])],
            'name' => ['required', 'string']
        ]);

        $data = Round::create($request->only(['competition_id' ,'mode' ,'name']));

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
        $round = Round::findOrFail($id);
        Round::where(['id' => $id])->update([
            'competition_id' => $request->competition_id ?? $round->competition_id,
            'mode' => $request->mode ?? $round->mode,
            'name' => $request->name ?? $round->name
        ]);

        return new GeneralResource($round);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
