<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Competition;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GeneralResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware(['role:hyperion', 'auth:api'], ['only' => ['store', 'update', 'delete']]);
    }
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
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id . ',id',
            'background_image' => 'required|url',
        ]);
        $category->update($request->only(['name', 'background_image']));

        return new GeneralResource($category);
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



    public function competitions(string $id): JsonResource
    {
        $data = Competition::where(['category_id' => $id])->get();
        return new GeneralResource($data);
    }
}
