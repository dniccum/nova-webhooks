<?php

namespace Dniccum\NovaWebhooks\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Created()
 * @method static static Updated()
 * @method static static Deleted()
 */
final class ModelEvents extends Enum
{
    const Created =   'created';
    const Updated =   'updated';
    const Deleted =   'deleted';
}
