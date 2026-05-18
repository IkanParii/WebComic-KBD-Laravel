<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $before = [
            'name' => $user->name,
            'email' => $user->email,
            'nama_publisher' => $user->nama_publisher,
        ];

        $validated = $request->validated();

        $validated['name'] = strip_tags((string) $validated['name']);

        if (isset($validated['nama_publisher'])) {
            $validated['nama_publisher'] = strip_tags((string) $validated['nama_publisher']);
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $changes = $user->getDirty();
        $user->save();

        foreach (['name', 'email', 'nama_publisher'] as $field) {
            if (! array_key_exists($field, $changes)) {
                continue;
            }

            $label = match ($field) {
                'name' => 'nama akun',
                'email' => 'email',
                'nama_publisher' => 'nama publisher',
                default => $field,
            };

            ActivityLogger::log(
                'profile_updated',
                sprintf(
                    '%s mengubah %s dari "%s" menjadi "%s".',
                    $user->name,
                    $label,
                    (string) ($before[$field] ?? '-'),
                    (string) ($user->{$field} ?? '-')
                ),
                $user,
                $request
            );
        }

        return Redirect::back()->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        ActivityLogger::log(
            'account_deleted',
            sprintf('%s (%s) menghapus akun sendiri.', $user->name, $user->role),
            $user,
            $request
        );

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
