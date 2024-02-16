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

class ParticipationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = User::with(['competitions' => function ($q) {
            $q->select(['competitions.id', 'title', 'category_id']);
        }])->where('id', $request->user()->id ?? 44)->select(['name', 'email', 'id'])->get();

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
        ]);

        if (!is_null($request->team_code)) {
            return $this->joinTeam($request, $request->team_code);
        }

        $user = User::findOrFail($request->user()->id);
        $competition = Competition::findOrFail($request->competition_id);

        $data = $competition->user()->attach($user, ['created_at' => now(), 'updated_at' => now(), 'team_code' => Str::random(6)]);

        return new GeneralResource($data);
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

        $minimum_size = $competition->minimum_size;
        $maximum_size = $competition->maximum_size;
        if ($request->team_size < $minimum_size || $request->team_size > $maximum_size) {
            throw ValidationException::withMessages([
                'team' => ["Incorrect Team Size."]
            ]);
        }
        $user = User::findOrFail(auth()->user()->id);

        $data = $competition->user()->attach($user, ['created_at' => now(), 'updated_at' => now(), 'team_code' => $code, 'team_name' => $request->team_name, 'team_size' => $request->team_size]);
        return new GeneralResource($data);
    }

    public function validateTeam(string $code, Competition $competition)
    {
        $count = DB::table('competition_user')->where('team_code', $code)->count();
        $minimum_size = $competition->minimum_size;
        $maximum_size = $competition->maximum_size;

        if ($count < $minimum_size || $count > $maximum_size) {
            throw ValidationException::withMessages([
                'team' => "The team must contain minimum {$minimum_size} and maximum of {$maximum_size} members.",
            ]);
        }
    }
}
