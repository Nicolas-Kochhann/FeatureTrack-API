<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feature\StoreFeatureRequest;
use App\Http\Requests\Feature\UpdateFeatureRequest;
use App\Models\Feature;
use App\Models\Project;
use Gate;
use Illuminate\Http\Request;

class FeatureController extends Controller
{

    public function list(Request $request, $projectId)
    {
        Gate::authorize('feature.list', $projectId);

        $eagerload = $request->has('eagerload');
        $query = Feature::query()->where('project_id', $projectId);

        if($eagerload) $query->with('steps');

        $features = $query->get();
        return response()->json($features, 200)->header('Content-Type','application/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request, $projectId)
    {
        Gate::authorize('feature.create', $projectId);
        
        $project = Project::findOrFail($projectId);
        $feature = $project->features()->create($request->only('name', 'description', 'links'));
        return response()->json($feature, 201)->header('Content-Type','application/json');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        Gate::authorize('feature.view', $id);

        $eagerload = $request->has('eagerload');
        $query = Feature::query();

        if($eagerload) $query->with('steps');

        $feature = $query->findOrFail($id);

        return response()->json($feature, 200)->header('Content-Type','application/json');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, $id)
    {
        Gate::authorize('feature.update/delete', $id);

        $feature = Feature::where('id', $id)->firstOrFail();
        $feature->update($request->only('name', 'description', 'links'));
        return response()->json($feature, 200)->header('Content-Type','application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('feature.update/delete', $id);

        $feature = Feature::where('id',$id)->firstOrFail();
        $feature->delete();
        return response()->json([], 204)->header('Content-Type','application/json');
    }
}
