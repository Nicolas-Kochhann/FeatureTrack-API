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
    public function list()
    {
        if(request()->has("name")){
            $projects = Project::where("name","LIKE","%".request()->get("name")."%")->get();
        }
        else {
            $projects = Project::all();
        }

        return response()->json($projects, 200)->header("Content-Type","application/json");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->all());
        return response()->json($project->id, 200)->header("Content-Type","application/json");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return response()->json($project, 200)->header("Content-Type","application/json");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());
        return response()->json($project->id, 200)->header("Content-Type", "application/json");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json([],200)->header("Content-Type", "application/json");
    }
}
