<?php 

namespace AsiRC\PhpHooks\Support;

class Hook
{
    public string $name;
    public $callback ;
    public int $priority;
   
    public function __construct(
        $name,
        $callback,
        $priority
    ){
        $this->name = $name;
        $this->callback = $callback;
        $this->priority = $priority;
    }

}
