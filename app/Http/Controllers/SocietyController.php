<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Society;

class SocietyController extends Controller
{


    // Read

    public function read($id):JsonResponse
    {
   
    $record = Society::find($id);

    if ($record) {
        return response()->json(['actualdata' => $record], 200);
    } else {
        return response()->json(['message' => 'Record not found'], 404);
    }
    }

    // Insert
     

    public function insert(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'logo' => 'required|mimes:jpg,png|max:2048', 
            'description' => 'required|string',
        ]);

        if ($request->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $request->errors()], 400);
        }
        $created = Society::create($request->all());


        if ($created) {
            return response()->json(['message' => 'Record created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create record'], 500);
        }
    }

    // update
    
    public function update(Request $request, $id):JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|mimes:jpg,png|max:2048', 
            'description' => 'required|string',
        ]);

        if ($request->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $request->errors()], 400);
        }

        // Find the record by its ID
        $record = Society::find($id);

      
        if ($record) {
           
            $record->update($request->all());

            return response()->json(['message' => 'Record updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Record not found'], 404);
        }   
    }


    // delete
    public function delete($id):JsonResponse
    {
    
    $record = Society::find($id);

    if ($record) {
        
        $record->delete();

        return response()->json(['message' => 'Record deleted successfully'], 200);
    } else {
        return response()->json(['message' => 'Record not found'], 404);
    }

    }
}