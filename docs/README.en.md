<p align="center"><img src="https://res.cloudinary.com/fayne/image/upload/v1787643269/fayne_logo.png" width="300"></p>

# Laravel Filament Cascade

[简体中文](../README.md) | English

For **PHP 8.4+, Laravel 13 and Filament 5**.

## Installation

Configure authorized Composer sources for this private package and its required `fayne/*` dependencies in the host first.

```bash
composer require fayne/laravel-filament-cascade
```

Laravel discovers the service provider automatically. Field-only packages need no Panel plugin. See [composer.json](../composer.json) for exact dependencies; do not copy private credentials from a development host.

## Features, usage and boundaries

Provides hierarchical selection with server-side path validation. Pass a nested static `options` array directly, or use configured `fromFile`, `fromDirectory`, or `fromUrl` sources. Dynamic sources must be explicitly allowlisted in `filament-cascade.sources`.

```php
use Fayne\Cascade\Forms\Components\CascadeField;

CascadeField::make('location')->cascadeOptions([
    'options' => [
        ['value' => 'north', 'label' => 'North', 'children' => [
            ['value' => 'coast', 'label' => 'Coast', 'leaf' => true],
        ]],
    ],
]);
```

## Testing and deployment

The package includes standalone Testbench boot tests (`composer test`). Verify rendering, input, save/reload, invalid values, and authorization in the host. Documentation checks are not runtime acceptance.

## Cooperation and contributions

Open an issue with reproduction steps, versions and expected behavior. Include relevant checks and outstanding environment acceptance in pull requests. Never submit credentials or sensitive business data. For commercial cooperation and licensing, contact [Fayne Guo](mailto:fayne.guo@gmail.com).

## License

[Proprietary license](../LICENSE.md). Copying, distribution and use require authorization. Third-party dependencies retain their own licenses.

## Development and security

[Development and CI](../CONTRIBUTING.md) · [Security reports](../SECURITY.md). Run `composer check` for syntax, formatting and available standalone tests. Do not commit `composer.lock`.
