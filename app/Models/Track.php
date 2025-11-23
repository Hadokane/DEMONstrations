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
        'user_id','title', 'album', 'audio_file_path','cover_image_path','visibility','duration_ms','play_count',
    ];

    protected $casts = 
    [
        'duration_ms' => 'integer',
        'play_count' => 'integer',
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

    public function getStatsAttribute()
    {
        $plays     = $this->plays->count();
        $likes     = $this->reactions->where('type', 'like')->count();
        $dislikes  = $this->reactions->where('type', 'dislike')->count();
        $laughs    = $this->reactions->where('type', 'laugh')->count();
        $wows      = $this->reactions->where('type', 'wow')->count();
        $sads      = $this->reactions->where('type', 'sad')->count();

        $totalVotes = $likes + $dislikes;
        $approval   = $totalVotes > 0
            ? round(($likes / $totalVotes) * 100)
            : null;

        return (object)[
            'plays'     => $plays,
            'likes'     => $likes,
            'dislikes'  => $dislikes,
            'laughs'    => $laughs,
            'wows'      => $wows,
            'sads'      => $sads,
            'approval'  => $approval,
            'comments'  => $this->comments->count(),
            'totalVotes'=> $totalVotes,
        ];
    }

}
