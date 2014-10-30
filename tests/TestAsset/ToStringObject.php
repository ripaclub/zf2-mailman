<?php
/**
 * ZF2 Mail Manager
 *
 * @link        https://github.com/ripaclub/zf2-mailman
 * @copyright   Copyright (c) 2014, RipaClub
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MailManTest\TestAsset;

/**
 * Class ToStringObject
 */
class ToStringObject
{
    protected $array;

    /**
     * @param $element
     * @return $this
     */
    public function addElement($element)
    {
        $this->array[] = $element;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(' ', $this->array);
    }
}
