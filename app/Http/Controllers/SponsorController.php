<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralResource;
use App\Models\Sponsor;
use App\Models\User;
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
            'society_id' => 'required|integer|exists:societies,id',
            'logo' => 'required|url',
            'title' => 'required|string',
            'company_name' => 'required|string',
            'web_url'  => 'required|url',
        ]);

        $data = Sponsor::create($request->only(['logo', 'title', 'company_name', 'web_url', 'society_id']));

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
            'society_id' => 'required|integer|exists:societies,id',
            'logo' => 'required|url',
            'title' => 'required|string',
            'company_name' => 'required|string',
            'web_url'  => 'required|url',
        ]);

        $record = Sponsor::findOrFail($id);
        $update = $record->update($request->only(['logo', 'title', 'company_name', 'web_url', 'society_id']));

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

    public function getbytitle()
    {
        $sponsors = Sponsor::all();

        $groupedsponsors = $sponsors->groupBy('title');

        return new GeneralResource($groupedsponsors);
    }

    public function uploadSponsorImage(Request $request)
    {
        $request->validate([
            'sponsor_task' => ['required', 'image', 'max:2048'],
        ]);

        $user = User::findOrFail($request->user()->id);
        if ($request->hasFile('sponsor_task')) {
            $file = $request->file('sponsor_task')->store('sponsor_task');
        } else {
            $file = null;
        }
        $user->update([
            'sponsor_task' => $file,
        ]);

        return new GeneralResource($user);
    }
}
