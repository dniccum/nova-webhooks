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
* [Using the Tool](#using-the-tool)
  * [Model Traits](#model-traits) 
  * [Customizing the Payload](#customizing-the-payload)
  * [Model Label](#model-label)
* [Testing](#testing)

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

## Using the Tool

### Model Traits

The main functionality of this tool is to listen to native Eloquent model events and passes a JSON-serialized payload of that model's attributes. A series traits are available for you to enable different actions and customize that hook's payload. 

This package provides 4 different traits that you can add to each of your Eloquent models:

- `Dniccum\NovaWebhooks\Traits\CreatedWebhook`
- `Dniccum\NovaWebhooks\Traits\UpdatedWebhook`
- `Dniccum\NovaWebhooks\Traits\DeletedWebhook`
- `Dniccum\NovaWebhooks\Traits\AllWebhooks`

Each trait, with exception for the last (which is a shortcut to include all available traits), is associated with the event that it listens for; `CreatedWebhook` for the created Eloquent event and so on and so forth.

#### Customizing the Payload

Additionally each trait provides a corresponding method to modify the payload that is sent with each webhook. See below for the name of the trait with the matching name of the method:

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

### Webhook Resource

The resource that is part of this tool will automatically be registered within Nova due to the trait that was added in the previous step.

## Testing

To perform the necessary PHP Unit tests using the Orchestra Workbench, clone the repository, install the necessary dependencies with `composer install` and run the PHP Unit testing suite:

```bash
./vendor/bin/phpunit
```
