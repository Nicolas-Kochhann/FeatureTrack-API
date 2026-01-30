<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $name = $request->query('name');
        $eagerload = $request->has('eagerload');

        $query = Project::query();

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
        $project = Project::create($request->only(['name', 'description']));
        return response()->json($project, 201)->header("Content-Type","application/json");
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $lazyload = $request->has('lazyload');
        $query = Project::query();
        
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
        $project = Project::findOrFail($id);
        $project->update($request->only(["name","description"]));
        return response()->json($project, 200)->header("Content-Type", "application/json");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response()->json([],204)->header("Content-Type", "application/json");
    }
}
