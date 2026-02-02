<?php

namespace App\Observers;

use App\Enums\FeatureStatus;
use App\Models\Step;

class StepObserver
{
    /**
     * Handle the Step "created" event.
     */
    public function created(Step $step): void
    {
        //
    }

    /**
     * Handle the Step "updated" event.
     */
    public function updated(Step $step): void
    {
        if(!$step->isDirty("completed")) return;

        $feature = $step->feature;

        $uncompletedSteps = $feature->steps()->where('completed', false)->count();
        $totalSteps = $feature->steps()->count();

        if($uncompletedSteps === 0){
            if($feature->status !== FeatureStatus::COMPLETED){
                $feature->status = FeatureStatus::COMPLETED;
            }
        } else if($uncompletedSteps > 0 && $uncompletedSteps < $totalSteps) {
            if($feature->status !== FeatureStatus::IN_PROGRESS){
                $feature->status = FeatureStatus::IN_PROGRESS;
            }
        } else {
            if($feature->status !== FeatureStatus::PENDING){
                $feature->status = FeatureStatus::PENDING;
            }
        }
        $feature->save();
    }

    /**
     * Handle the Step "deleted" event.
     */
    public function deleted(Step $step): void
    {
        //
    }

    /**
     * Handle the Step "restored" event.
     */
    public function restored(Step $step): void
    {
        //
    }

    /**
     * Handle the Step "force deleted" event.
     */
    public function forceDeleted(Step $step): void
    {
        //
    }
}
