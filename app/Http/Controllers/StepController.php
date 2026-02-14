<?php

namespace App\Http\Controllers;

use App\Http\Requests\Step\StoreStepRequest;
use App\Http\Requests\Step\UpdateStepRequest;
use App\Models\Feature;
use App\Models\Step;
use Gate;

class StepController extends Controller
{

    public function list($featureId)
    {
        Gate::authorize('step.list', $featureId);

        $feature = Feature::findOrFail($featureId);
        $steps = $feature->steps()->get();
        return response()->json($steps, 200)->header('Content-Type','applicatio/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($featureId, StoreStepRequest $request)
    {
        Gate::authorize('step.create', $featureId);

        $feature = Feature::findOrFail($featureId);
        $step = $feature->steps()->create($request->only('title'));
        return response()->json($step, 201)->header('Content-Type','application/json');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStepRequest $request, $id)
    {
        Gate::authorize('step.update/delete', $id);

        $step = Step::findOrFail($id);
        $step->update($request->only('title', 'completed'));
        return response()->json($step, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('step.update/delete', $id);        

        $step = Step::findOrFail($id);
        $step->delete();
        return response()->json([], 204)->header('Content-Type','application/json');
    }
}
