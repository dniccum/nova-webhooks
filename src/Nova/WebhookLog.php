<?php

namespace Dniccum\NovaWebhooks\Nova;

use Dniccum\NovaWebhooks\Models\WebhookLog as WebhookModel;

use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class WebhookLog extends WebhookResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = WebhookModel::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'error_code',
        'error_message',
    ];

    /**
     * Get the fields displayed by the Webhook resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Number::make(__('nova-webhooks::nova.error_code'), 'error_code')
                ->sortable()
                ->readonly(),

            Textarea::make(__('nova-webhooks::nova.error_message'), 'error_message')
                ->alwaysShow()
                ->readonly(),

            Date::make(__('nova-webhooks::nova.created_at'), 'created_at')
                ->sortable()
                ->readonly()
                ->displayUsing(function ($date) {
                    return \Carbon\Carbon::make($date)->format(config('nova-webhooks.date_format'));
                }),
        ];
    }

    /**
     * Build a "relatable" query for the given resource.
     *
     * This query determines which instances of the model may be attached to other resources.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('successful', false);
    }
}
