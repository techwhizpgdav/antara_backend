<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GeneralResource;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = User::with(['competitions' => function ($q) {
            $q->select(['competitions.id', 'title', 'category_id']);
        }])->where('id', $request->user()->id)->select(['name', 'email', 'id'])->get();

        return new GeneralResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'team_code' => 'nullable|exists:competition_user,team_code',
            'team' => 'required|boolean',
            'team_size' => 'nullable|integer|min:1',
            'sponsor_link' => 'nullable|url:https',
            'screenshot' => 'nullable|image|size:2048'
        ]);

        if (DB::table('competition_user')->where(['user_id' => $request->user()->id, 'competition_id' => $request->competition_id])->exists()) {
            return response()->json(['message' => 'You can participate only once.']);
        }

        if (!is_null($request->team_code)) {
            return $this->joinTeam($request, $request->team_code);
        }

        $user = User::findOrFail($request->user()->id);
        $competition = Competition::findOrFail($request->competition_id);

        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payments');
        } else {
            $path = null;
        }

        $team_code = Str::random(6);

        $data = $competition->user()->attach(
            $user,
            [
                'created_at' => now(), 'updated_at' => now(), 'team_code' => $team_code, 'team_name' => $request->team_name,
                'team_size' => $request->team_size ?? 1, 'team' => $request->team, 'remarks' => $request->remarks, 'sponsor_link' => $request->sponsor_link,
                'payment_ss' => $path, 'leader' => 1
            ]
        );

        return new GeneralResource(['team' => $request->team, 'team_code' => $team_code]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = User::with(['competitions' => function ($q) use ($id) {
            $q->select(['competitions.id', 'title', 'category_id']);
            $q->where('competition_user.id', $id);
        }])->where('id', auth()->user()->id)->select(['name', 'email', 'id'])->get();

        return new GeneralResource($data);
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
        return DB::table('competition_user')->where(['id' => $id, 'user_id' => auth()->user()->id])->delete();
    }

    /**
     * Join the team using team code.
     */
    public function joinTeam(Request $request, string $code)
    {
        $record = DB::table('competition_user')->where('team_code', $code)->first();
        if (!$record) {
            throw ValidationException::withMessages([
                'team' => ["Team Does not exist."]
            ]);
        }

        $competition = Competition::findOrFail($record->competition_id);
        $this->validateTeam($code, $competition);

        $user = User::findOrFail(auth()->user()->id);

        $data = $competition->user()->attach($user, ['created_at' => now(), 'updated_at' => now(), 'team_code' => $code, 'team_name' => $record->team_name, 'team_size' => $record->team_size, 'team' => $record->team]);
        return new GeneralResource(['team' => $record->team, 'team_code' => $code]);
    }

    public function validateTeam(string $code, Competition $competition)
    {
        $count = DB::table('competition_user')->where('team_code', $code)->count();
        $minimum_size = $competition->minimum_size;
        $maximum_size = $competition->maximum_size;

        if ($count >= $maximum_size) {
            throw ValidationException::withMessages([
                'team' => "The team must contain minimum {$minimum_size} and maximum of {$maximum_size} members.",
            ]);
        }
    }

    public function myTeam(Request $request)
    {
        $participation = DB::table('competition_user')
            ->where(['user_id' => $request->user()->id, 'team' => 1])
            ->join('competitions', 'competitions.id', '=', 'competition_user.competition_id')
            ->select('competition_user.*', 'competitions.title', 'competitions.paid_event')
            ->get();
        if (!$participation) {
            throw ValidationException::withMessages([
                'team' => "No team exist for this user. Please create a team or join an existing one.",
            ]);
        }

        return new GeneralResource($participation);
    }

    public function teamDetails(Request $request, string $code)
    {
        $authorization = DB::table('competition_user')->where(['team_code' => $code, 'user_id' => $request->user()->id])->first();
        if (!$authorization) {
            return response()->json(['message' => 'Team does not exist'], 403);
        }

        $team = Competition::with([
            'user' => function ($q) use ($code) {
                $q->wherePivot('team_code', $code)->withPivot('allowed');
            }
        ])
            ->where('id', $authorization->competition_id)
            ->get();

        return new GeneralResource($team);
    }
}
