<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\Sponsor;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Sponsor::all();
        return new GeneralResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'logo' => 'required|url',
            'title' => 'required|string',
            'company_name' => 'required|string',
            'web_url'  => 'required|url',
        ]);

        $data = Sponsor::create($request->only(['logo','title','company_name','web_url']));

        return new GeneralResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $record = Sponsor::findOrFail($id);
        return new GeneralResource($record);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'logo' => 'required|url',
            'title' => 'required|string',
            'company_name' => 'required|string',
            'web_url'  => 'required|url',
        ]);

        $record = Sponsor::findOrFail($id);
        $update = $record->update($request->only(['logo','title','company_name','web_url']));

        return response()->json(['data' => $update], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Sponsor::findOrFail($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete], 200);
    }
}