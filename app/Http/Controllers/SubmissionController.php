<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new GeneralResource(User::with(["competitionSubmissions"])->where('id', $request->user()->id)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'competition_id' => ['required', 'exists:competitions,id'],
            'url' => ['required', 'url:https'],
            'remarks' => ['nullable', 'string']
        ]);

        $user = User::findOrFail($request->user()->id);
        $competition = Competition::findOrFail($request->competition_id);

        $user->competitionSubmissions()->attach($competition, ['url' => $request->url, 'remarks' => $request->remarks, 'created_at' => now(), 'updated_at' => now()]);

        return new GeneralResource($user);
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
