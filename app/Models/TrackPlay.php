<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackPlay extends Model
{
    /** @use HasFactory<\Database\Factories\TrackPlayFactory> */
    use HasFactory;

    protected $fillable = 
    [
        'track_id','user_id',
    ];

    public function track() 
    { 
        return $this->belongsTo(Track::class); 
    }

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

}
