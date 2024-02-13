<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\GeneralResource;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $usersRounds = $user->societyCompetitions->flatMap(function ($competition) {
            return $competition->rules;
        });
        return new GeneralResource($usersRounds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'round_id' => ['required', 'exists:rounds,id'],
            'statement' => ['required', 'string'],
        ]);

        $data = Rule::create($request->only(['round_id', 'statement']));

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
