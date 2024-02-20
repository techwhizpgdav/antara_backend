<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return new GeneralResource(User::with(["competitionSubmissions" => function ($q) {
            $q->withPivot(['message', 'status', 'remarks', 'created_at']);
        }])->where('id', $request->user()->id)->get());
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

        $participation = DB::table('competition_user')->where(['user_id' => $request->user()->id, 'competition_id' => $request->competition_id, 'leader' => 1])->first();
        if (!$participation) {
            return response()->json(['message' => 'Please make sure you are registered for this competition.'], 404);
        }
        $submission = DB::table('submissions')->where(['user_id' => $request->user()->id, 'competition_id' => $request->competition_id])->exists();

        if ($submission) {
            return response()->json(['message' => "You have already submitted your entry for this competition."], 400);
        }

        $user->competitionSubmissions()->attach($competition, [
            'url' => $request->url, 'remarks' => $request->remarks, 'created_at' => now(), 'updated_at' => now(),
            'team_size' => $participation->team_size, 'team_code' => $participation->team_code,
            'leader' => $participation->leader
        ]);

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
