<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function log(
        string $event,
        string $description,
        ?User $user = null,
        ?Request $request = null,
        ?string $actorName = null,
        ?string $actorRole = null
    ): void
    {
        ActivityLog::create([
            'user_id' => $user?->id,
            'actor_name' => $actorName ?? $user?->name ?? 'Guest',
            'actor_role' => $actorRole ?? $user?->role,
            'event' => $event,
            'description' => $description,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
