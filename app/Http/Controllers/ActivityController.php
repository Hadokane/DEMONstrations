<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user   = $request->user();
        $tab    = $request->query('tab', 'tracks'); 
        $search = $request->query('search');
        $filter = $request->query('filter', 'all');
        $sort   = $request->query('sort', 'newest');

        $perPage = 10;

        if ($tab === 'comments') 
        {
            $query = $user->comments()->with('track')->latest();

            if ($search) 
            {
                $query->where(function ($q) use ($search) 
            {
                    $q->where('body', 'like', "%{$search}%")
                      ->orWhereHas('track', fn ($t) =>
                          $t->where('title', 'like', "%{$search}%")
                      );
                });
            }

            $items = $query->paginate($perPage)->withQueryString();
        } 
        elseif ($tab === 'reactions') 
        {
            $query = $user->reactions()->with('track')->latest();

            if ($search) 
            {
                $query->whereHas('track', fn ($t) =>
                    $t->where('title', 'like', "%{$search}%")
                );
            }

            $items = $query->paginate($perPage)->withQueryString();
        } 
        else 
        {
            $tab = 'tracks';

            $query = $user->tracks()->withCount(['plays', 'reactions', 'comments'])->latest();


            if ($filter === 'mine') 
            {
                $query->where('user_id', $user->id);
            } 
            elseif ($filter === 'public') 
            {
                $query->where('visibility', 'public');
            } 
            elseif ($filter === 'private') 
            {
                $query->where('visibility', 'private')
                    ->where('user_id', $user->id);
            } 
            elseif ($filter === 'shared') 
            {
                $query->where('visibility', 'private')
                    ->whereHas('accesses', fn ($q) => $q->where('user_id', $user->id));
            } 
            else 
            {
                $query->where(function ($q) use ($user) 
                {
                    $q->where('visibility', 'public')
                    ->orWhere('user_id', $user->id)
                    ->orWhereHas('accesses', fn ($q2) => $q2->where('user_id', $user->id));
                });
            }

            switch ($sort) 
            {
                case 'popularity':
                    $query->orderByDesc('play_count');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'reactions':
                    $query->orderByDesc('reactions_count');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }

            if ($search) 
            {
                $query->where('title', 'like', "%{$search}%");
            }

            $items = $query->paginate($perPage)->withQueryString();
        }

        return view('activity.index', [
            'user'   => $user,
            'tab'    => $tab,
            'search' => $search,
            'filter' => $filter,
            'sort'   => $sort,
            'items'  => $items,
        ]);
    }
}
