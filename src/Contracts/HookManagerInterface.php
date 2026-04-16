<?php

namespace AsiRC\PhpHooks\Contracts;

interface HookManagerInterface{
    public function addAction(string $hook, callable $callback, int $priority = 10): void;
    public function doAction(string $hook, ...$params): void;

    public function addFilter(string $hook, callable $callback, int $priority = 10): void;
    public function doFilter(string $hook,$value, ...$params);
}