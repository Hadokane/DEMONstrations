<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount(['tracks', 'comments'])
            ->orderBy('artist_name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'artist_name' => ['required', 'string', 'max:255', 'unique:users,artist_name'],
            'first_name'  => ['required', 'string', 'max:255'],
            'last_name'   => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin'    => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'artist_name' => $data['artist_name'],
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'is_admin'    => $data['is_admin'] ?? false,
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['tracks', 'comments.track']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'artist_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class, 'artist_name')->ignore($user->id),
            ],

            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'password'    => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_admin'    => ['sometimes', 'boolean'],
        ]);

        $user->update([
            'artist_name' => $data['artist_name'],
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'email'       => $data['email'],
            'is_admin'    => $data['is_admin'] ?? false,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('status', 'User updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('status', 'You cannot delete your own admin account.');
        }

        $user->delete();
        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User deleted.');
    }
}
