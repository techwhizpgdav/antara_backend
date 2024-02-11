<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\CompetitionResource;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Competition::with(['category', 'society'])->get();

        return new CompetitionResource($data);
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
            'start_at' => 'required|date_format:H:i:s',
            'ends_at' => 'required|date_format:H:i:s',
            'date' => 'required|date',
            'venue' => 'required|string',
            'paid_event' => 'required|boolean',
            'team_fee' => 'required_if:paid_event,true',
            'individual_fee' => 'required_if:paid_event,true',
            'upi_id' => 'required_if:paid_event,true'
        ]);

        $data = Competition::create($request->only(
            ['category_id', 'title', 'image_url', 'rules', 'queries_to', 'description', 'minimum_size', 'maximum_size', 'start_at', 'ends_at', 'date', 'venue', 'team_fee', 'individual_fee', 'upi_id', 'paid_event']
        ));

        return new CompetitionResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = Competition::with(['category', 'society', 'rounds.rules'])->findOrFail($id);
        return new CompetitionResource($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:200|unique:competitions,title,' . $id . ',id',
            'image_url' => 'required|url',
            'description' => 'required|string',
            'minimum_size' => 'required|integer|min:1',
            'maximum_size' => 'required|integer|min:1',
            'start_at' => 'required|date_format:H:i:s',
            'ends_at' => 'required|date_format:H:i:s',
            'date' => 'required|date',
            'venue' => 'required|string',
            'paid_event' => 'required|boolean',
            'team_fee' => 'required_if:paid_event,true',
            'individual_fee' => 'required_if:paid_event,true',
            'upi_id' => 'required_if:paid_event,true'
        ]);

        $record = Competition::findOrFail($id);
        $update = $record->update($request->only(
            ['category_id', 'title', 'image_url', 'rules', 'queries_to', 'description', 'minimum_size', 'maximum_size', 'start_at', 'ends_at', 'date', 'venue', 'team_fee', 'individual_fee', 'upi_id', 'paid_event']
        ));

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
        $data = Competition::select(DB::raw('DAYNAME(date) as day'), 'id', 'title', 'date')
            ->get()
            ->groupBy('day');

        return new GeneralResource($data);
    }
}
