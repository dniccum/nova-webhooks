<?php

namespace Dniccum\NovaWebhooks\Traits;

trait AllWebhooks
{
    use CreatedWebhook;
    use DeletedWebhook;
    use UpdatedWebhook;
}
