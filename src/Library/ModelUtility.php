<?php

namespace Dniccum\NovaWebhooks\Library;

use Dniccum\NovaWebhooks\Contracts\WebhookModel;
use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Traits\DeletedWebhook;
use Symfony\Component\ClassLoader\ClassMapGenerator;
use Dniccum\NovaWebhooks\Traits\CreatedWebhook;
use Dniccum\NovaWebhooks\Traits\UpdatedWebhook;

class ModelUtility
{
    public static function availableModelActions()
    {
        $models = [];
        $classMaps = ClassMapGenerator::createMap(config('nova-webhooks.model_location'));

        foreach($classMaps as $class => $filePath) {
            $classes = class_uses_recursive($class);
            $classes = array_keys($classes);

            $model = new WebhookModel($class);
            if (in_array(CreatedWebhook::class, $classes)) {
                $model->addAction(ModelEvents::Created);
            }
            if (in_array(UpdatedWebhook::class, $classes)) {
                $model->addAction(ModelEvents::Updated);
            }
            if (in_array(DeletedWebhook::class, $classes)) {
                $model->addAction(ModelEvents::Deleted);
            }

            array_push($models, $model);
        }

        return $models;
    }
}
