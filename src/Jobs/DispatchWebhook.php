<?php

namespace Dniccum\NovaWebhooks\Jobs;

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
     * Create a new job instance.
     *
     * @param Model $model
     * @param string $action
     * @param array $payload
     * @return void
     */
    public function __construct($model, string $action = 'created', array $payload = [])
    {
        $this->model = $model;
        $this->action = $action;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        WebhookUtility::processWebhooks($this->model, $this->action, $this->payload);
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
