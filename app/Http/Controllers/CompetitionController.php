<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\CompetitionResource;
use App\Models\SocietyUser;
use App\Models\User;
use Carbon\Carbon;

class CompetitionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:member|hyperion'], ['only' => ['index', 'store']]);
        $this->middleware('role:member', ['only' => ['store', 'update', 'delete']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $data = $user->societyCompetitions;
        return new GeneralResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'image_url' => 'required|url',
            'description' => 'required|string',
            'minimum_size' => 'required|integer|min:1',
            'maximum_size' => 'required|integer|min:1',
            'start_at' => 'required|date_format:H:i',
            'ends_at' => 'required|date_format:H:i',
            'date' => 'required|date',
            'venue' => 'required|string',
            'paid_event' => 'required|boolean',
            'team_fee' => 'required_if:paid_event,true',
            'individual_fee' => 'required_if:paid_event,true',
            'upi_id' => 'required_if:paid_event,true',
            'tag_line' => 'nullable|string',
            'sponsor_task' => 'nullable|boolean',
            'remarks' => 'boolean',
            'remarks_label' => 'nullable|string'
        ]);

        $society = SocietyUser::where('user_id', auth()->user()->id)->first();

        if (!$society) {
            abort(403, "This action is unauthorized");
        }

        $data = Competition::create(
            [
                'society_id' => $society->society_id,
                'tag_line' => $request->tag_line,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'image_url' => $request->image_url,
                'queries_to' => $request->queries_to,
                'description' => $request->description,
                'minimum_size' => $request->minimum_size,
                'maximum_size' => $request->maximum_size,
                'start_at' => Carbon::parse($request->start_at)->format('H:i:s'),
                'ends_at' => Carbon::parse($request->ends_at)->format('H:i:s'),
                'date' => $request->date,
                'venue' => $request->venue,
                'team_fee' => $request->team_fee,
                'individual_fee' => $request->individual_fee,
                'upi_id' => $request->upi_id,
                'paid_event' => $request->paid_event,
                'sponsor_task' => $request->sponsor_task,
                'remarks' => $request->remarks,
                'remarks_label' => $request->remarks_label,
            ]
        );

        return new CompetitionResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Competition::with(['category', 'society', 'rounds.rules'])->findOrFail($id);
        if (auth()->check()) {
            $check = DB::table('competition_user')->where(['user_id' => auth()->user()->id, 'competition_id' => $id])->exists();
            if ($check) {
                $participated = true;
            } else {
                $participated = false;
            }
        } else {
            $participated = false;
        }
        return new CompetitionResource(['competition' => $record, 'participated' => $participated]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competition $competition)
    {
        $this->authorize('update', $competition);
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:200|unique:competitions,title,' . $competition->id . ',id',
            'image_url' => 'required|url',
            'description' => 'required|string',
            'minimum_size' => 'required|integer|min:1',
            'maximum_size' => 'required|integer|min:1',
            'start_at' => 'required',
            'ends_at' => 'required',
            'date' => 'required|date',
            'venue' => 'required|string',
            'paid_event' => 'required|boolean',
            'team_fee' => 'required_if:paid_event,true',
            'individual_fee' => 'required_if:paid_event,true',
            'upi_id' => 'required_if:paid_event,true',
            'sponsor_task' => 'nullable|boolean',
            'remarks' => 'boolean',
            'remarks_label' => 'nullable|string'
        ]);

        $update = $competition->update([
            'tag_line' => $request->tag_line,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'image_url' => $request->image_url,
            'queries_to' => $request->queries_to,
            'description' => $request->description,
            'minimum_size' => $request->minimum_size,
            'maximum_size' => $request->maximum_size,
            'start_at' => Carbon::parse($request->start_at)->format('H:i:s'),
            'ends_at' => Carbon::parse($request->ends_at)->format('H:i:s'),
            'date' => $request->date,
            'venue' => $request->venue,
            'team_fee' => $request->team_fee,
            'individual_fee' => $request->individual_fee,
            'upi_id' => $request->upi_id,
            'paid_event' => $request->paid_event,
            'sponsor_task' => $request->sponsor_task,
            'remarks' => $request->remarks,
            'remarks_label' => $request->remarks_label,
        ]);

        return response()->json(['data' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Competition::findOrFail($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete]);
    }

    public function compByDay()
    {
        $data = Competition::select(DB::raw('DAYNAME(date) as day'), 'competitions.id', 'title', 'date', 'start_at', 'ends_at', 'name', 'venue', 'society_id')
            ->join('societies', 'societies.id', '=', 'competitions.society_id')
            ->orderByRaw('DAY(date), start_at')
            ->get()
            ->groupBy('date');

        // $coll = $data->map(function ($item) {
        //     return $item->map(function ($order) {
        //         return collect($order)->sortBy('start_at');
        //     });
        // });
        // return $coll;

        return $data;

        return new GeneralResource($data);
    }
}
