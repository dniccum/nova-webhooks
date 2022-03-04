<?php

namespace Dniccum\NovaWebhooks\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebhookLogPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any webhook.
     *
     * @return boolean
     */
    public function viewAny()
    {
        return true;
    }

    /**
     * Determine whether the user can view a webhook.
     *
     * @param User $user
     * @return boolean
     */
    public function view($user)
    {
        return true;
    }
}
