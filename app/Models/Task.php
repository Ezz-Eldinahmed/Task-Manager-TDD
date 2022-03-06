<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory, RecordActivity;

    protected $touches = ['project'];

    protected $fillable = ['body', 'completed'];

    protected $casts = ['completed' => 'boolean'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
