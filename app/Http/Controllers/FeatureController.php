<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feature\StoreFeatureRequest;
use App\Http\Requests\Feature\UpdateFeatureRequest;
use App\Models\Feature;

class FeatureController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request)
    {
        $feature = Feature::create($request->only('name', 'description', 'links'));
        return response()->json($feature, 201)->header('Content-Type','application/json');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $feature = Feature::with('steps')->findOrFail($id);
        return response()->json($feature,200)->header('Content-Type','application/json');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        $feature->update($request->only('name', 'description', 'links'));
        return response()->json($feature,200)->header('','application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        $feature->delete();
        return response()->json([], 204)->header('Content-Type','application/json');
    }
}
