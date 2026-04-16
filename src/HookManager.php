<?php

namespace AsiRC\PhpHooks;

use AsiRC\PhpHooks\Contracts\HookManagerInterface;
use AsiRC\PhpHooks\Support\Hook;

/**
 * Hook manager for registering and executing actions and filters.
 *
 * Actions are invoked for side effects only. Filters are executed in
 * sequence and may transform a value before it is returned.
 */
class HookManager implements HookManagerInterface
{
    protected array $actions = [];
    protected array $filters = [];

    /**
     * Register an action callback for a hook.
     *
     * @param string   $hook     The hook name.
     * @param callable $callback The callback to execute.
     * @param int      $priority Execution priority, lower values run earlier.
     */
    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        $this->actions[$hook][] = new Hook($hook, $callback, $priority);
    }

    /**
     * Execute all action callbacks registered for the hook.
     *
     * The provided parameters are forwarded to each callback in order.
     */
    public function doAction(string $hook, ...$params): void
    {
        if (empty($this->actions[$hook])) {
            return;
        }

        $hooks = $this->sortHooks($this->actions[$hook]);

        foreach ($hooks as $hookObject) {
            call_user_func_array($hookObject->callback, $params);
        }
    }

    /**
     * Register a filter callback for a hook.
     *
     * @param string   $hook     The hook name.
     * @param callable $callback The callback to execute.
     * @param int      $priority Execution priority, lower values run earlier.
     */
    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][] = new Hook($hook, $callback, $priority);
    }

    /**
     * Apply all filter callbacks to the given value.
     *
     * Each callback receives the current value plus any additional parameters.
     * The returned value from one callback is passed to the next.
     *
     * @param string $hook   The hook name.
     * @param mixed  $value  The value to filter.
     * @param mixed  ...$params Additional parameters forwarded to callbacks.
     *
     * @return mixed The final filtered value.
     */
    public function doFilter(string $hook, $value, ...$params)
    {
        if (empty($this->filters[$hook])) {
            return $value;
        }

        $hooks = $this->sortHooks($this->filters[$hook]);

        foreach ($hooks as $hookObject) {
            $value = call_user_func_array($hookObject->callback, array_merge([$value], $params));
        }

        return $value;
    }

    /**
     * Sort hook objects by priority.
     *
     * Lower priority values are executed before higher values.
     *
     * @param Hook[] $hooks
     * @return Hook[]
     */
    protected function sortHooks(array $hooks): array
    {
        usort(
            $hooks,
            fn($a, $b) => $a->priority <=> $b->priority
        );

        return $hooks;
    }
}
