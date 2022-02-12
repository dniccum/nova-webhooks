<?php

namespace Dniccum\NovaWebhooks\Traits;

/**
 * Enables all the available webhooks for this model
 * @package dniccum/nova-webhooks
 */
trait AllWebhooks
{
    use CreatedWebhook;
    use DeletedWebhook;
    use UpdatedWebhook;
}
