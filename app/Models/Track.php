<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    /** @use HasFactory<\Database\Factories\TrackFactory> */
    use HasFactory;

    protected $fillable = 
    [
        'user_id','title','artist', 'album', 'audio_file_path','cover_image_path','visibility','duration_ms','play_count',
    ];

    public function owner() 
    { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function comments() 
    { 
        return $this->hasMany(Comment::class); 
    }

    public function reactions()
    { 
        return $this->hasMany(Reaction::class); 
    }

    public function plays()     
    { 
        return $this->hasMany(TrackPlay::class); 
    }

    public function accesses() 
    { 
        return $this->hasMany(TrackAccess::class); 
    }

    protected $casts = 
    [
        'duration_ms' => 'integer',
        'play_count' => 'integer',
    ];

    public function likesCount(): int 
    { 
        return $this->reactions()->where('type','like')->count(); 
    }

    public function dislikesCount(): int 
    { 
        return $this->reactions()->where('type','dislike')->count(); 
    }

    public function laughsCount(): int 
    { 
        return $this->reactions()->where('type','laugh')->count(); 
    }

    public function wowCount(): int 
    { 
        return $this->reactions()->where('type','wow')->count(); 
    }

    public function sadCount(): int 
    { 
        return $this->reactions()->where('type','sad')->count(); 
    }

}
