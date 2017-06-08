<?php

namespace Brightfox\Traits;

trait HasFullName
{
    public function getFullNameAttribute() {
        $full_name = $this->name;
        if(!is_null($this->middle_name) || $this->middle_name != ''){
            $full_name .= ' '.$this->middle_name;
        }
        $full_name .= ' '.$this->last_name;

        return $full_name;
    }
}