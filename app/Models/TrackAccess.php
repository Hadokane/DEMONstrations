<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackAccess extends Model
{
    /** @use HasFactory<\Database\Factories\TrackAccessFactory> */
    use HasFactory;

    protected $fillable = ['track_id','user_id','granted_by'];

    protected $casts = ['expires_at' => 'datetime'];

    public function track() 
    { 
        return $this->belongsTo(Track::class); 
    }

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function granter() 
    { 
        return $this->belongsTo(User::class, 'granted_by'); 
    }

}
