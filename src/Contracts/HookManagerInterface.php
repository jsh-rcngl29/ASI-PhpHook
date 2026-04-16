<?php

namespace AsiRC\PhpHooks\Contracts;

interface HookManagerInterface
{
    /**
     * Register an action callback for the given hook name.
     *
     * Action callbacks are executed when doAction() is called.
     *
     * @param string   $hook     The hook name.
     * @param callable $callback The callback to execute.
     * @param int      $priority Lower values execute earlier.
     */
    public function addAction(string $hook, callable $callback, int $priority = 10): void;

    /**
     * Execute all callbacks registered for the given action hook.
     *
     * @param string $hook   The hook name.
     * @param mixed  ...$params Optional parameters forwarded to each callback.
     */
    public function doAction(string $hook, ...$params): void;

    /**
     * Register a filter callback for the given hook name.
     *
     * Filter callbacks receive a value, may modify it, and return the new value.
     *
     * @param string   $hook     The hook name.
     * @param callable $callback The callback to execute.
     * @param int      $priority Lower values execute earlier.
     */
    public function addFilter(string $hook, callable $callback, int $priority = 10): void;

    /**
     * Apply all registered filter callbacks to the given value.
     *
     * Each callback receives the current filtered value plus any extra parameters.
     * The returned value from one filter is passed to the next.
     *
     * @param string $hook   The hook name.
     * @param mixed  $value  The initial value to filter.
     * @param mixed  ...$params Optional parameters forwarded to each callback.
     *
     * @return mixed The final filtered value.
     */
    public function doFilter(string $hook, $value, ...$params);
}