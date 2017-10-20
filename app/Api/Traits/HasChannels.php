<?php

namespace GetCandy\Api\Traits;

use Carbon\Carbon;

trait HasChannels
{
    public function scopeChannel($query, $channel = null)
    {
        $roles = app('api')->roles()->getHubAccessRoles();
        $groups = $this->getCustomerGroups();
        $user = app('auth')->user();
        $channels = app('api')->channels();

        if ($user && $user->hasAnyRole($roles)) {
            return $query;
        }

        // If no channel is set, we need to get the default one.
        if (!$channel) {
            $channel = $channels->getDefaultRecord()->handle;
        }

        return $query->whereHas('channels', function ($query) use ($channel) {
            $query->whereHandle($channel)->whereDate('published_at', '<', Carbon::now());
        });
    }
}