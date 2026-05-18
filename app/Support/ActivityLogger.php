<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function log(string $event, string $description, ?User $user = null, ?Request $request = null): void
    {
        ActivityLog::create([
            'user_id' => $user?->id,
            'actor_name' => $user?->name ?? 'Guest',
            'actor_role' => $user?->role,
            'event' => $event,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
