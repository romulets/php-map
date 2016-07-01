<?php
/**
 * Created by PhpStorm.
 * User: polvolabs
 * Date: 01/07/16
 * Time: 09:43
 */

namespace Massaroth\Map;


abstract class Map implements \Iterator
{

    /**
     * @var mixed
     */
    protected $first;

    /**
     * @var mixed
     */
    protected $last;

    /**
     * @var mixed
     */
    protected $current;

    /**
     * @var int
     */
    protected $size = 0;

    /**
     * @param $key
     * @param $value
     * @return void
     */
    abstract public function put($key, $value);

    /**
     * @param $search
     * @return mixed
     */
    abstract public function get($search);

    /**
     * @param $key
     * @return void
     */
    abstract public function remove($key);

    /**
     * @return void
     */
    abstract public function clear();

    /**
     * @param $search
     * @return bool
     */
    abstract public function containsKey($search);

    /**
     * @param $search
     * @return bool
     */
    abstract public function containsValue($search);

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->size() === 0;
    }

    /**
     * @return int
     */
    public function size()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->first;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return $this->last;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->current;
    }

}