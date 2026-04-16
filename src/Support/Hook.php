<?php 

namespace Asi\PhpHooks\Support;

class Hook
{
   
    public function __construct(
        public string $name,
        public $callback ,
        public int $priority
    ){}

}
