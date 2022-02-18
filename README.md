# Webhook Manager for Laravel Nova

A Laravel Nova tool that allows users to create and manage webhooks based on Eloquent Model events.

![Nova Webhooks](https://github.com/dniccum/nova-webhooks/blob/main/assets/nova-webhooks-social-image.png?raw=true)

[![Latest Version on Packagist](https://poser.pugx.org/dniccum/nova-webhooks/v/stable?format=flat-square&color=#0E7FC0)](https://packagist.org/packages/dniccum/nova-webhooks)
[![License](https://poser.pugx.org/dniccum/nova-webhooks/license?format=flat-square)](https://packagist.org/packages/dniccum/nova-webhooks)
[![Total Downloads](https://poser.pugx.org/dniccum/nova-webhooks/downloads?format=flat-square)](https://packagist.org/packages/dniccum/nova-webhooks)

A tool for Laravel's Nova administrator panel that enables users to create webhooks that can be customized to fire on specified Eloquent model events (created, updated, etc). This allows applications to communicate with other applications and integrations (Zapier, If This Then That, etc).

## Table of Contents

* [Installation](#installation)
* [Configuration](#configuration)
* [Implementing the Tool](#implementing-the-tool)
  * [Nova Resource](#nova-resource) 
  * [Model Traits](#model-traits) 
  * [Customizing the Payload](#customizing-the-payload)
  * [Model Label](#model-label)
  * [Sending Webhooks to a Queue](#sending-webhooks-to-a-queue)
* [Using the Tool](#using-the-tool)
  * [Webhook Secret](#webhook-secret) 
  * [Authorization](#authorization) 
  * [Testing Action](#testing-action)
* [Testing and Development](#testing-and-development)

## Installation

You can install the package via composer:

```bash
composer require dniccum/nova-documentation
```

You will then need to publish the package's assets to your application:

```bash
php artisan vendor:publish --provider="Dniccum\NovaWebhooks\ToolServiceProvider"
```

Doing this action will add the following items:

- migrations
- configuration files *(Note: there will be two)*
- translations

There are two things that you will need to add to your application's `NovaServiceProvider.php`:

#### Addition 1 - Resource Trait

To inform Nova of the new resource that exists outside of the Nova directory within the application, we need to add a trait to the `NovaServiceProvider` class:

```php
use Dniccum\NovaWebhooks\Nova\UsesWebhookResource;

...

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    // Add the following trait to your service provider
    use UsesWebhookResource;

}
```

#### Addition 2 - Tool Registration

Finally, you will need to register the tool within the NovaServiceProvider.php:

```php
use Dniccum\NovaWebhooks\NovaWebhooks;

...

/**
 * Get the tools that should be listed in the Nova sidebar.
 *
 * @return array
 */
public function tools()
{
    return [
        // other tools
        new NovaWebhooks,
    ];
}
```

## Configuration

Two different configuration files are published with this package; one for this package (`nova-webhooks.php`) and one for the webhook server (`webhook-server.php`) that this package utilizes.

This package relies on [Spatie's webhook server package](https://github.com/spatie/laravel-webhook-server) to dispatch each webhook request. Feel free to configure the server to your needs using the associated documentation. 

## Implementing the Tool

While this package is not necessarily "plug-and-play," it has been designed to only require minor amounts of code to get your webhooks up and running.

### Nova Resource

The Webhook resource that is part of this tool will automatically be registered within Nova due to the trait that was added during setup process.

### Model Traits

The main functionality of this tool is to listen to native Eloquent model events and passes a JSON-serialized payload of that model's attributes. A series traits are available for you to enable different actions and customize that hook's payload. 

This package provides 4 different traits that you can add to each of your Eloquent models:

- `Dniccum\NovaWebhooks\Traits\CreatedWebhook`
- `Dniccum\NovaWebhooks\Traits\UpdatedWebhook`
- `Dniccum\NovaWebhooks\Traits\DeletedWebhook`
- `Dniccum\NovaWebhooks\Traits\AllWebhooks`

Each trait, with exception for the last (which is a shortcut to include all available traits), is associated with the event that it listens for; `CreatedWebhook` for the created Eloquent event and so on and so forth.

#### Customizing the Payload

Additionally, each trait provides a corresponding method to modify the payload that is sent with each webhook. See below for the name of the trait with the matching name of the method:

| Trait             | Method                  |
|-------------------|-------------------------|
| `CreatedWebhook`  | `createdWebhookPayload` |
| `UpdatedWebhook`  | `updatedWebhookPayload` |
| `DeleteddWebhook` | `deletedWebhookPayload` |

By default, this package will send a serialized array of the model that extends one of these traits. However you do have the ability to modify this behavior.

##### Custom Array

Return an associative array of valid key/value pairs based on the `$model` parameter.

```php
protected static function createdWebhookPayload($model)
{
    return [
        'id' => $model->id,
        'name' => $model->first_name.' '.$model->last_name,
        'email' => $model->email,
        // And so on
    ];
}
```

##### JSON Resource

You also have the ability to return a [Laravel-generated JSON resource](https://laravel.com/docs/9.x/eloquent-resources) and customize this object to your liking:

**JSON Resource**

```php
class PageLikeResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
        ];
    }
}
```

**Eloquent Model**

```php
protected static function createdWebhookPayload($model)
{
    return new PageLikeResource($model);
}
```

#### Model Label

Each trait also exposes a method that will configure how the model displays within the Nova interface. By default Nova will show the namespaced string of the Model followed by the associated action; for instance `App\Models\User:updated`.

If you would like to change it to something else a little more user-friendly, or even use a translation key, you can modify it like so:

```php
/**
 * The name of the model that will be applied to the webhook
 *
 * @return string
 */
public static function webhookLabel() : string
{
    return 'App Users';
}
```

Setting the label to *App Users* will show the following in the Nova action: `App Users:updated`.

#### Sending Webhooks to a Queue

When an Eloquent Model executes a webhook call, the [webhook server package](https://github.com/spatie/laravel-webhook-server#usage) that helps power this tool will automatically send the webhook to the configured queue. In the case that you might be hydrating additional relationships and/or other logic while generating your payload, it might be more performant to send the execution of the webhook to the queue as well.

To do this, simply add the `ShouldQueueWebhook` trait to the top of your model, like so:

```php
use Dniccum\NovaWebhooks\Jobs\DispatchWebhook;

class PageLike extends \Illuminate\Database\Eloquent\Model
{
    use ShouldQueueWebhook
    
    ...
}
```

This adds both a property and an additional method to your model:

```php
/**
 * The job class that should be used to dispatch this model's webhook
 *
 * @var string
 */
public static $job = DispatchWebhook::class;

/**
 * @return bool
 */
public static function queueWebhook() : bool
{
    return true;
}
```

The property gives you the ability to override the Job class that is called when executing the webhook. For most cases this should not have to be changed, but it is available if necessary. Furthermore, you also have the ability to write custom logic to whether or not send the webhook to the queue at all. Again, most likely you shouldn't have to change this.

## Using the Tool

### Webhook Secret

The fields that the "baked-in" Webhook resource provides are relatively self-explanatory. All fields are required except for the hook's secret key. This will be auto-generated for you if you do not provide one your self. In most cases, your webhooks will not need a secret key for validation; both Zapier and IFTTT do not require any authorization.

In the circumstance that you are using a package/service like [Spatie's webhook client](https://github.com/spatie/laravel-webhook-client), it is usually recommended (per their documentation) that you use a key authorize your application's webhook endpoints to prevent any unwanted requests. Again, you can either use the auto-generated hash that this package provides, or you can enter your own.

### Authorization

Due to the fact the Webhook resource that is made available to the application via this package, you can provide user/role authorization using a [conventional Eloquent Model policy](https://laravel.com/docs/9.x/authorization#policy-methods). Refer to the [Nova documentation](https://nova.laravel.com/docs/3.0/resources/authorization.html) for additional information.

### Testing Action

Probably the most important part of any webhook is testing and validation that your webhook is working correctly and providing the necessary information to the prescribed endpoint. This package gives you the ability to do such an action based on the Eloquent events that you have selected for this webhook. Simply select the hook that you want to test and then utilize the Nova Actions toolbar or the inline button to launch the testing action, and then indicate which model and corresponding model event that you want to test. 

![Action Modal](https://github.com/dniccum/nova-webhooks/blob/main/assets/action-modal.png?raw=true)

#### Sample Data

When you want execute a test, this package will pull a random entry in the selected model's table in your database and use it as the subject for your webhook. If you don't have any records available yet, the action will throw an error instructing you to add the necessary records before you proceed.

## Testing and Development

To perform the necessary PHP Unit tests using the Orchestra Workbench, clone the repository, install the necessary dependencies with `composer install` and run the PHP Unit testing suite:

```bash
./vendor/bin/phpunit
```
