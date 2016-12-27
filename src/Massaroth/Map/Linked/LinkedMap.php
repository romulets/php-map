<?php

namespace Massaroth\Map\Linked;

use Massaroth\Map\Exception\EmptyKeyException;
use Massaroth\Map\Map;

/**
 * Created by PhpStorm.
 * User: Rômulo Farias
 * Date: 28/06/16
 * Time: 10:45
 */
class LinkedMap extends Map
{

    /**
     * @param $key
     * @param $value
     * @throws EmptyKeyException
     * @return void
     */
    public function put($key, $value)
    {
        $item = $this->searchItemByKey($key) ?: $this->getNewItem($key);
        $item->setValue($value);
    }

    /**
     * @param $search
     * @return mixed|null
     */
    public function get($search)
    {
        $item = $this->searchItemByKey($search);
        return $item ? $item->getValue() : null;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->current = $this->first;
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->setFirstAndCurrentItemEqually(null);
        $this->setLastItem(null);
        $this->size = 0;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->current = $this->valid() ? $this->current->getNext() : null;
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->valid() ? $this->current->getKey() : null;
    }

    /**
     * @param $search
     * @return bool
     */
    public function containsKey($search)
    {
        return !is_null($this->searchItemByKey($search));
    }

    /**
     * @param $search
     * @return bool
     */
    public function containsValue($search)
    {
        return !is_null($this->searchItemByValue($search));
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return !$this->isEmpty() ? $this->first->getValue() : null;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return !$this->isEmpty() ? $this->last->getValue() : null;
    }

    /**
     * @return array
     */
    public function keys()
    {
        $keys = [];
        foreach ($this as $key => $value) {
            $keys[] = $key;
        }
        return $keys;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->valid() ? $this->current->getValue() : null;
    }

    /**
     * Checks if current position is valid
     * @return boolean
     */
    public function valid()
    {
        return !is_null($this->current);
    }

    /**
     * @param $key
     * @return void
     */
    public function remove($key)
    {
        $item = $this->searchItemByKey($key);
        $previousItem = $this->searchPreviousItem($item);

        if (is_null($item)) return;

        $this->size--;
        $this->checkupMapAndRemoveItem($item, $previousItem);
    }
 
    /**
     * @param LinkedMapItem $itemToRemove
     * @param LinkedMapItem $previousItem
     */
    private function checkupMapAndRemoveItem(LinkedMapItem $itemToRemove, LinkedMapItem $previousItem = null) {
        $nextItem = $itemToRemove->getNext();

        if ($this->isEmpty()) {
            $this->clear();
        } elseif ($itemToRemove === $this->first) {
            $this->setFirstAndCurrentItemEqually($nextItem);
        } elseif ($itemToRemove === $this->last) {
            $this->setLastItem($previousItem);
        } else {
            $this->linkItems($previousItem, $nextItem);
        }
    }

    /**
     * @param LinkedMapItem $back
     * @param LinkedMapItem $front
     */
    private function linkItems(LinkedMapItem $back, LinkedMapItem $front) {
        $back->setNext($front);
    }

    /**
     * @param $key
     * @return LinkedMapItem
     */
    private function getNewItem($key)
    {
        $item = new LinkedMapItem($key, null);

        if ($this->isEmpty()) {
            $this->setFirstAndCurrentItemEqually($item);
        }

        $this->setLastItem($item);
        $this->size++;
        return $item;
    }

    /**
     * @param LinkedMapItem|null $item
     */
    private function setFirstAndCurrentItemEqually(LinkedMapItem $item = null)
    {
        $this->setFirstItem($item);
        $this->setCurrentItem($item);
    }

    /**
     * @param LinkedMapItem $item
     */
    private function setFirstItem(LinkedMapItem $item = null)
    {
        $this->first = $item;
    }

    /**
     * @param LinkedMapItem $item
     */
    private function setCurrentItem(LinkedMapItem $item = null)
    {
        $this->current = $item;
    }

    /**
     * @param LinkedMapItem $item
     */
    private function setLastItem(LinkedMapItem $item = null)
    {
        if (!is_null($this->last)) {
            $this->last->setNext($item);
        }

        $this->last = $item;
    }

    /**
     * @param $item
     * @return LinkedMapItem|null
     */
    private function searchPreviousItem($item)
    {
        return $this->searchItem(function (LinkedMapItem $item, $search) {
            return $item->getNext() === $search;
        }, $item);
    }

    /**
     * @param $search
     * @return LinkedMapItem|null
     */
    private function searchItemByKey($search)
    {
        return $this->searchItem(function (LinkedMapItem $item, $search) {
            return $item->getKey() === $search;
        }, $search);
    }

    /**
     * @param $search
     * @return LinkedMapItem|null
     */
    private function searchItemByValue($search)
    {
        return $this->searchItem(function (LinkedMapItem $item, $search) {
            return $item->getValue() === $search;
        }, $search);
    }

    /**
     * @param \Closure $searchMethod Expects bool return
     * @param $search
     * @return LinkedMapItem|null
     */
    private function searchItem(\Closure $searchMethod, $search)
    {
        $foundItem = null;
        $initialCurrent = $this->current;
        $this->rewind();
        $next = $this->current;
        while ($next) {

            if ($searchMethod($next, $search)) {
                $foundItem = $next;
                break;
            }

            $this->current = $next;
            $next = $this->current ? $this->current->getNext() : null;
        }

        $this->current = $initialCurrent;
        return $foundItem;
    }
    
}