<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $name = $request->query('name');
        $eagerload = $request->has('eagerload');

        $query = Auth::user()->projects();

        if($name)
        {
            $query->where('name','like','%'.$name.'%');
        }
        if($eagerload)
        {
            $query->with('features');
        }

        $projects = $query->get();

        return response()->json($projects, 200)->header("Content-Type","application/json");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Auth::user()->projects()->create($request->only(['name', 'description']));
        return response()->json($project, 201)->header("Content-Type","application/json");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        Gate::authorize('project.view', $id);
        
        $lazyload = $request->has('lazyload');
        $query = Project::query()->where('id', $id);
        
        if($lazyload){
            $query->with('features');
        }

        $project = $query->get();
        return response()->json($project, 200)->header("Content-Type","application/json");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        Gate::authorize('project.update/delete', $id);

        $project = Project::findOrFail($id);
        $project->update($request->only(["name","description"]));
        return response()->json($project, 200)->header("Content-Type", "application/json");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('project.update/delete', $id);

        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json([],204)->header("Content-Type", "application/json");
    }
}
