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
        $users = User::all();

        $audioDir = storage_path('app/seed/audio');
        $coverDir = storage_path('app/seed/covers');

        $audioFiles = collect(File::files($audioDir));
        $coverFiles = collect(File::files($coverDir));

        if ($audioFiles->isEmpty()) 
        {
            return;
        }
        
        $audioCount = $audioFiles->count();
        $coverCount = $coverFiles->count();
        $tracksToCreate = 25;
        $adminTracksToCreate = 6;

        for ($i = 0; $i < $tracksToCreate; $i++) 
        {
            $audioFile = $audioFiles[$i % $audioCount];
            $audioExtension = strtolower($audioFile->getExtension());
            $audioPath = 'tracks/' . Str::uuid() . '.' . $audioExtension;

            Storage::disk('public')->put(
                $audioPath,
                File::get($audioFile->getRealPath())
            );

            $coverPath = null;
            if ($coverCount > 0 && rand(1, 100) <= 70) 
            {
                $coverFile = $coverFiles[$i % $coverCount];
                $coverExt  = strtolower($coverFile->getExtension());
                $coverPath = 'covers/' . Str::uuid() . '.' . $coverExt;

                Storage::disk('public')->put(
                    $coverPath,
                    File::get($coverFile->getRealPath())
                );
            }

            Track::create([
                'user_id'          => $users->random()->id,
                'title'            => Str::title(Str::random(10)),
                'audio_file_path'  => $audioPath,
                'cover_image_path' => $coverPath,
                'visibility'       => rand(0, 1) ? 'public' : 'private',
                'play_count'       => rand(0, 3000),
            ]);
        }

        for ($i = 0; $i < $adminTracksToCreate; $i++) 
        {

            $audioFile = $audioFiles[$i % $audioCount];
            $audioExt  = strtolower($audioFile->getExtension());
            $audioPath = 'tracks/' . Str::uuid() . '.' . $audioExt;

            Storage::disk('public')->put(
                $audioPath,
                File::get($audioFile->getRealPath())
            );

            $coverPath = null;
            if ($coverCount > 0 && rand(1, 100) <= 70) 
            {
                $coverFile = $coverFiles[$i % $coverCount];
                $coverExt  = strtolower($coverFile->getExtension());
                $coverPath = 'covers/' . Str::uuid() . '.' . $coverExt;
                Storage::disk('public')->put(
                    $coverPath,
                    File::get($coverFile->getRealPath())
                );
            }

            Track::create([
                'user_id'          => $admin->id,
                'title'            => Str::title(Str::random(10)),
                'audio_file_path'  => $audioPath,
                'cover_image_path' => $coverPath,
                'visibility'       => rand(0, 1) ? 'public' : 'private',
                'play_count'       => rand(0, 3000),
            ]);
        }
    }
}   