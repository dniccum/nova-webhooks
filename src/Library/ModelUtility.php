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

    /**
     * @param array $settings
     * @return WebhookModel[]
     */
    public static function parseSavedList(array $settings) : array
    {
        $models = [];

        foreach($settings as $action => $selected) {
            if ($selected) {
                $class = \Str::before($action, ':');
                $actionName = \Str::after($action, ':');
                $model = new WebhookModel($class);
                $models[$action] = $model->label.':'.$actionName;
            }
        }

        return $models;
    }

    /**
     * Returns all the available models in the application's namespace
     *
     * @return Collection
     */
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
     * Compile the available models and actions to select for the webhook
     *
     * @return array
     */
    public static function fieldArray() : array
    {
        $models = self::availableModelActions();
        $array = [];

        foreach($models as $model) {
            foreach($model->actions as $action) {
                $array[$model->actionName($action)] = $model->label($action);
            }
        }

        return collect($array)
            ->sort()
            ->all();
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

    /**
     * @return string
     */
    private static function namespace()
    {
        if (\App::runningUnitTests()) {
            return "Dniccum\NovaWebhooks\Tests\Models\\";
        }

        return Container::getInstance()->getNamespace();
    }
}
