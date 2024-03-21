<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Volunteer extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;
    protected $guarded = [];

    public function event( ){ 
        return $this->belongsTo(Event::class); 
    }

    protected $appends = ['age'];
    
    public function getAgeAttribute()
    {
    return Carbon::parse($this->attributes['birthdate'])->age;
    
    }
}
