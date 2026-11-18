<?php

namespace App\Policies;

use App\Models\Guide;
use App\Models\User;

class GuidePolicy
{
    /**
     * Determine whether the user can view any guides in CMS.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can view the guide.
     */
    public function view(?User $user, Guide $guide): bool
    {
        if ($guide->status === 'published') {
            return true;
        }

        // Drafts can only be viewed by the author or an administrator
        if (! $user) {
            return false;
        }

        return $user->isAdmin() || $user->id === $guide->author_id;
    }

    /**
     * Determine whether the user can create guides.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can update the guide.
     */
    public function update(User $user, Guide $guide): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEditor() && $user->id === $guide->author_id;
    }

    /**
     * Determine whether the user can delete the guide.
     */
    public function delete(User $user, Guide $guide): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isEditor() && $user->id === $guide->author_id;
    }
}
