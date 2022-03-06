<?php

namespace App\Models;

trait RecordActivity
{
    public $oldAttributes = [];

    public static function bootRecordActivity()
    {
        static::updating(function ($model) {
            $model->oldAttributes = $model->getOriginal();
        });
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function recordActivity($description)
    {
        if (auth()->check()) {
            $this->activity()->create(
                [
                    'user_id' => auth()->user()->id,
                    'description' => $description,
                    'changes' => $this->activityChanges(),
                    'project_id' => class_basename($this) == 'Project' ? $this->id : $this->project_id,
                ]
            );
        }
    }

    protected function activityChanges()
    {
        return ($this->wasChanged()) ?
            [
                'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after' =>  array_except($this->getChanges(), 'updated_at')
            ] : null;
    }
}
