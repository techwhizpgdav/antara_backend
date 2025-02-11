<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\GeneralResource;
use App\Models\Round;

class RuleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'role:member|hyperion'], ['only' => ['index']]);
        $this->middleware(['auth:api', 'role:member'], ['only' => ['store', 'update']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        // $usersRounds = $user->societyCompetitions->flatMap(function ($competition) {
        //     return $competition->rules;
        // });

        $usersRounds = Round::whereHas('competition', function($comp) use ($user){
            $comp->whereHas('society', function($society) use ($user){
                $society->whereHas('users', function($userQuery) use ($user){
                    $userQuery->where('users.id', $user->id);
                });
            });
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
    public function update(Request $request, Rule $rule)
    {
        $rule->update([
            'statement' => $request->statement ?? $rule->statement
        ]);

        return new GeneralResource($rule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Rule::where('id', $id)->delete();
        return $data;
    }
}
