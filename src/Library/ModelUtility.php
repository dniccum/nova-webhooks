<?php

namespace Dniccum\NovaWebhooks\Library;

use Dniccum\NovaWebhooks\Contracts\WebhookModel;
use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Traits\DeletedWebhook;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Dniccum\NovaWebhooks\Traits\CreatedWebhook;
use Dniccum\NovaWebhooks\Traits\UpdatedWebhook;

class ModelUtility
{
    /**
     * @return WebhookModel[]
     */
    public static function availableModelActions() : array
    {
        $models = [];
        $availableModels = self::getModels();

        foreach($availableModels as $class) {
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

    public static function getModels(): Collection
    {
        $modelFiles = File::allFiles(self::path());
        $models = collect($modelFiles)
            ->map(function($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('\%s%s',
                    self::namespace(),
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

                return $class;
            })
            ->filter(function($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new \ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) &&
                        !$reflection->isAbstract();
                }

                return $valid;
            });

        return $models->values();
    }

    /**
     * @return string
     */
    private static function path() : string
    {
        if (\App::runningUnitTests()) {
            return __DIR__.'/../../tests/Models';
        }

        return app_path();
    }

    private static function namespace()
    {
        if (\App::runningUnitTests()) {
            return "Dniccum\NovaWebhooks\Tests\Models\\";
        }

        return Container::getInstance()->getNamespace();
    }
}
