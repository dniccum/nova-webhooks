<?php

namespace Dniccum\NovaWebhooks\Nova;

use Dniccum\NovaWebhooks\Nova\Actions\WebhookTestAction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Webhook extends WebhookResource
{
    /**
     * Get the fields displayed by the Webhook resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make(__('nova-webhooks::nova.name'), 'name')
                ->help(__('nova-webhooks::nova.name_help'))
                ->placeholder(__('nova-webhooks::nova.name_placeholder'))
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('nova-webhooks::nova.url'), 'url')
                ->help(__('nova-webhooks::nova.url_help'))
                ->placeholder('https://hooks.zapier.com/hooks/catch/abcd1234')
                ->sortable()
                ->rules('required', 'url'),

            Text::make(__('nova-webhooks::nova.secret'), 'secret')
                ->help(__('nova-webhooks::nova.secret_help'))
                ->hideFromIndex()
                ->placeholder(null)
                ->updateRules('required', 'string', 'min:10', 'max:100')
                ->creationRules('nullable', 'string'),

            $this->optionGroup()
                ->help(__('nova-webhooks::nova.available_actions_help')),

            BelongsTo::make(__('nova-webhooks::nova.last_modified_by'), 'modifiedBy', config('nova-webhooks.users.resource'))
                ->exceptOnForms()
                ->readonly(),
        ];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new WebhookTestAction($this->model()))
                ->confirmButtonText(__('nova-webhooks::nova.test')),
        ];
    }
}
