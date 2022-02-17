# Webhook Manager for Laravel Nova

A Laravel Nova tool that allows users to create and manage webhooks based on Eloquent Model events.

![Nova Webhooks](https://github.com/dniccum/nova-webhooks/blob/master/assets/nova-webhooks-social-image.png?raw=true)

[![Latest Version on Packagist](https://poser.pugx.org/dniccum/nova-webhooks/v/stable?format=flat-square&color=#0E7FC0)](https://packagist.org/packages/dniccum/nova-webhooks)
[![License](https://poser.pugx.org/dniccum/nova-webhooks/license?format=flat-square)](https://packagist.org/packages/dniccum/nova-webhooks)
[![Total Downloads](https://poser.pugx.org/dniccum/nova-webhooks/downloads?format=flat-square)](https://packagist.org/packages/dniccum/nova-webhooks)

A tool for Laravel's Nova administrator panel that enables users to create webhooks that can be customized to fire on specified Eloquent model events (created, updated, etc). This allows applications to communicate with other applications and integrations (Zapier, If This Then That, etc).

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

## Using the Tool

### Webhook Resource

The resource that is part of this tool will automatically be registered within Nova due to the trait that was added in the previous step.

## Testing

To perform the necessary PHP Unit tests using the Orchestra Workbench, clone the repository, install the necessary dependencies with `composer install` and run the PHP Unit testing suite:

```bash
./vendor/bin/phpunit
```
