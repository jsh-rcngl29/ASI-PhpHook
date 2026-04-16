<?php

namespace AsiRC\PhpHooks;

use AsiRC\PhpHooks\Contracts\HookManagerInterface;
use AsiRC\PhpHooks\Support\Hook;

class HookManager implements HookManagerInterface
{
    protected array $actions = [];
    protected array $filters = [];

    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        $this->actions[$hook][] = new Hook($hook, $callback, $priority);
    }

    public function doAction(string $hook, ...$params): void
    {
        if (empty($this->actions[$hook]))
            return;

        $hooks = $this->sortHooks($this->actions[$hook]);

        foreach ($hooks as $hookObject) {
            call_user_func_array($hookObject->callback, $params);
        }
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        $this->filters[$hook][] = new Hook($hook, $callback, $priority);
    }

    public function doFilter(string $hook, $value, ...$params)
    {
        if (empty($this->filters[$hook]))
            return;

        $hooks = $this->sortHooks($this->filters[$hook]);

        foreach ($hooks as $hookObject) {
            $nValue = [];
            if (!is_array($value)) {
                $nValue[] = $value;
            }else{
                 $nValue = $value;
            }
            call_user_func_array($hookObject->callback, array_merge($nValue, $params));
        }

        return $value;
    }

    protected function sortHooks(array $hooks): array
    {
        usort(
            $hooks,
            fn($a, $b) => $a->priority <=> $b->priority
        );

        return $hooks;
    }

}
