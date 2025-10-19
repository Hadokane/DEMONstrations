<?php

namespace Database\Seeders;

use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email','admin@example.com')->firstOrFail();

        // Use demo audio for seeding from public folder
        $sourceDir = base_path('database/seeders/audio');
        $files = collect(File::files($sourceDir))
                    ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['mp3']))
                    ->values();

        foreach ($files as $i => $file) {
                    $ext  = strtolower($file->getExtension());
                    $dest = 'tracks/'.Str::uuid().'.'.$ext;

                    Storage::disk('public')->put($dest, File::get($file->getPathname()));

                    Track::create([
                        'user_id'         => $admin->id,
                        'title'           => $file->getFilenameWithoutExtension(),
                        'artist'          => 'DEMONstration',
                        'audio_file_path' => $dest,   
                        'visibility'      => $i === 0 ? 'private' : 'public',
                        'play_count'      => 0,
                    ]);
                }


        // Public track (everyone can see)
        Track::factory()->create([
            'user_id'    => $admin->id,
            'title'      => 'Public Demo Track',
            'visibility' => 'public',
        ]);

        // Private track (owner-only)
        Track::factory()->create([
            'user_id'    => $admin->id,
            'title'      => 'Private Demo Track',
            'visibility' => 'private',
        ]);

        // 3 Additional tracks
        Track::factory()->count(3)->create([
            'user_id' => $admin->id,
        ]);
    }
}
