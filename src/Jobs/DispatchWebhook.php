<?php

namespace Dniccum\NovaWebhooks\Jobs;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DispatchWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $action;

    /**
     * @var array
     */
    public $payload;

    /**
     * @var bool
     */
    public bool $isTest = false;

    /**
     * Create a new job instance.
     *
     * @param Model $model
     * @param string $action
     * @param array $payload
     * @param bool $isTest
     * @return void
     */
    public function __construct($model, string $action = ModelEvents::Created, array $payload = [], bool $isTest = false)
    {
        $this->model = $model;
        $this->action = $action;
        $this->payload = $payload;
        $this->isTest = $isTest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        WebhookUtility::processWebhooks($this->model, $this->action, $this->payload, $this->isTest);
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        // Send user notification of failure, etc...
    }
}
