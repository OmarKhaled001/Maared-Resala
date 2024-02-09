<?php

namespace App\Models;

use App\Models\Volunteer;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Event extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded = [];

    public function volunteers()
    { 
        return $this->belongsToMany(Volunteer::class ,'event_volunteer')->withTimestamps(); 
    }


}
