<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Competition;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Category::all();
        return new CategoryResource($data);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'background_image' => 'required|url',
        ]);

        $data = Category::create($request->only(['name', 'background_image']));
        return new CategoryResource($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $record = Category::findOrFail($id);
        return new CategoryResource($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id . ',id',
            'background_image' => 'required|url',
        ]);
        $record = Category::findOrFail($id);
        $update = $record->update($request->only(['name', 'background_image']));

        return response()->json(['data' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = Category::findOrFail($id);
        $delete = $record->delete();
        return response()->json(['data' => $delete]);
    }

    public function competitions(Category $category)
    {
        // Fetch competitions belonging to the category
        $competitions = $category->competitions()->get();

        return response()->json($competitions);
    }
}
