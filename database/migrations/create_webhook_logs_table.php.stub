<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookLogsTable extends Migration
{
    public function up()
    {
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->boolean('successful')
                ->default(true);
            $table->string('error_code')
                ->nullable();
            $table->text('error_message')
                ->nullable();
            $table->unsignedBigInteger('webhook_id')
                ->nullable();
            $table->foreign('webhook_id')
                ->references('id')
                ->on('webhooks');
            $table->timestamp('created_at')
                ->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('webhook_logs');
    }
}
