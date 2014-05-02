<?php

namespace MailModuleTest\TestAsset;

class ToStringObject
{
    protected $array;

    public function addElement($element)
    {
        $this->array[] = $element;
        return $this;
    }

    public function __toString()
    {
        return implode(' ', $this->array);
    }
}
