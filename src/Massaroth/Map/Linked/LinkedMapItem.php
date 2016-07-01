<?php

namespace Massaroth\Map\Linked;

use Massaroth\Map\Exception\EmptyKeyException;

/**
 * Created by PhpStorm.
 * User: Rômulo Farias
 * Date: 28/06/16
 * Time: 10:49
 */
class LinkedMapItem
{

    /**
     * @var mixed
     */
    private $key;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var LinkedMapItem
     */
    private $next;

    /**
     * LinkedMapItem constructor.
     * @param mixed $key
     * @param mixed $value
     * @param LinkedMapItem $next
     */
    public function __construct($key, $value, LinkedMapItem $next = null)
    {
        $this->setKey($key);
        $this->setValue($value);
        $this->setNext($next);
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $key
     * @throws EmptyKeyException
     */
    public function setKey($key)
    {
        if (is_null($key) || $key === false)
            throw new EmptyKeyException("A key can't be null or false");

        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return LinkedMapItem
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @param LinkedMapItem $next
     */
    public function setNext($next)
    {
        $this->next = $next;
    }
}