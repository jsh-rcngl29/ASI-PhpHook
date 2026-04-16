# PHP Hooks

A simple hook system for PHP that supports actions and filters with priority-based execution.

## Installation

### Install via Composer

```bash
composer require asi-rc/php-hooks
```

### Install from Git

If this package is hosted on Git, you can clone it and install dependencies locally:

```bash
git clone https://github.com/asi-rc/php-hooks.git
cd php-hooks
composer install
```

If you want to load the package directly from a VCS repository in another project, add it to your project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/asi-rc/php-hooks.git"
        }
    ],
    "require": {
        "asi-rc/php-hooks": "dev-main"
    }
}
```

Then run:

```bash
composer update asi-rc/php-hooks
```

## Usage

### Registering actions

Use `addAction()` to register a callback that runs when a hook is triggered with `doAction()`.

```php
use AsiRC\PhpHooks\HookManager;

$hooks = new HookManager();

$hooks->addAction('user_registered', function ($userId) {
    echo "User registered: {$userId}\n";
}, 10);

$hooks->doAction('user_registered', 42);
```

### Registering filters

Use `addFilter()` to register a callback that modifies a value. Each filter receives the current value and any additional parameters.

```php
$hooks->addFilter('format_name', function ($name) {
    return strtoupper($name);
}, 10);

$finalName = $hooks->doFilter('format_name', 'John Doe');

echo $finalName; // JOHN DOE
```

### Priority handling

Lower priority values run first. Multiple callbacks on the same hook will execute in order of priority.

```php
$hooks->addFilter('value', fn($value) => $value + 1, 20);
$hooks->addFilter('value', fn($value) => $value * 2, 10);

$result = $hooks->doFilter('value', 5);
// result = (5 * 2) + 1 = 11
```

## API

- `addAction(string $hook, callable $callback, int $priority = 10): void`
- `doAction(string $hook, ...$params): void`
- `addFilter(string $hook, callable $callback, int $priority = 10): void`
- `doFilter(string $hook, $value, ...$params)`

## Notes

- Actions are intended for side effects and do not return a transformed value.
- Filters receive a value and must return the updated value so it can be passed to the next filter.
