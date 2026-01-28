<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feature\StoreFeatureRequest;
use App\Http\Requests\Feature\UpdateFeatureRequest;
use App\Models\Feature;
use App\Models\Project;

class FeatureController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $feature = $project->features()->create($request->only('name', 'description', 'links'));
        return response()->json($feature, 201)->header('Content-Type','application/json');
    }

    /**
     * Display the specified resource.
     */
    public function show($projectId, $id)
    {
        $feature = Feature::where('project_id', $projectId)->where('id', $id)->with('steps')->firstOrFail();
        return response()->json($feature, 200)->header('Content-Type','application/json');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, $projectId, $id)
    {
        $feature = Feature::where('project_id', $projectId)->where('id', $id)->firstOrFail();
        $feature->update($request->only('name', 'description', 'links'));
        return response()->json($feature, 200)->header('Content-Type','application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($projectId, $id)
    {
        $feature = Feature::where('project_id', $projectId)->where('id',$id)->firstOrFail();
        $feature->delete();
        return response()->json([], 204)->header('Content-Type','application/json');
    }
}
