<?php

namespace Dniccum\NovaWebhooks\Nova\Actions;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Facades\WebhookModels;
use Dniccum\NovaWebhooks\Facades\Webhooks;
use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Dniccum\NovaWebhooks\Models\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use OwenMelbz\RadioField\RadioButton;

class WebhookTestAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * @inheritdoc
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * @inheritdoc
     * @var bool
     */
    public $showOnDetail = true;

    /**
     * @inheritdoc
     * @var bool
     */
    public $showOnTableRow = true;

    /**
     * @var Model|Webhook
     */
    protected $model;

    /**
     * @return string
     */
    public function name()
    {
        return __('nova-webhooks::nova.test_webhook');
    }

    /**
     * @param Model|Webhook $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        /**
         * @var Webhook $model
         */
        foreach ($models as $model) {
            $webhookAction = $fields->get('hook');

            $class = \Str::before($webhookAction, ':');
            $webhookModel = $class::inRandomOrder()->first();
            $actionName = \Str::after($webhookAction, ':');
            $actionName = ModelEvents::fromValue($actionName);

            if (empty($webhookModel)) {
                return Action::danger(__('nova-webhooks::nova.no_models_available', [ 'model' => $class ]));
            }

            if ($actionName->is(ModelEvents::Created)) {
                $class::createdWebhook($webhookModel, true);
            } elseif ($actionName->is(ModelEvents::Updated)) {
                $class::updatedWebhook($webhookModel, true);
            } elseif ($actionName->is(ModelEvents::Deleted)) {
                $class::deletedWebhook($webhookModel, true);
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            RadioButton::make(__('nova-webhooks::nova.webhook_to_test'), 'hook')
                ->options(
                    WebhookModels::parseSavedList((array) $this->model->settings)
                )
                ->stack() // optional (required to show hints)
                ->marginBetween() // optional
        ];
    }
}
