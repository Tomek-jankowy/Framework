<?php

namespace App\Core\Entity\Service;

class Entity
{
    public function get(string $name)
    {
        if(isset($this->$name)){
            return $this->$name;
        }
        return null;
    }

    public function set(string $name, mixed $value){
        if($this->get($name)!== null){
            $this->$name = $value;
        }
    }
}