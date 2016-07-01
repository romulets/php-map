<?php

namespace Massaroth\Tests\Map\Linked;

use Massaroth\Map\Exception\EmptyKeyException;
use Massaroth\Map\Linked\LinkedMap;
use PHPUnit\Framework\TestCase;


/**
 * Created by PhpStorm.
 * User: Rômulo Farias
 * Date: 28/06/16
 * Time: 11:27
 */
class LinkedMapTest extends TestCase
{

    /**
     * Verify if it works with scalar values
     */
    public function testPutScalarWithSuccess()
    {
        $hashMap = new LinkedMap();
        $hashMap->put(1, 2);
        $hashMap->put("a", "b");
        $hashMap->put(["023"], ["abc"]);

        $this->assertEquals(2, $hashMap->get(1));
        $this->assertEquals("b", $hashMap->get("a"));
        $this->assertEquals(["abc"], $hashMap->get(["023"]));
    }

    /**
     * Verify if it works with scalar values
     */
    public function testPutObjectWithSuccess()
    {
        $hashMap = new LinkedMap();
        $obj1 = new \stdClass();
        $obj1->a = true;

        $obj2 = new \stdClass();
        $obj1->b = true;


        $hashMap->put($obj1, 1);
        $hashMap->put(2, $obj2);

        $this->assertEquals(1, $hashMap->get($obj1));
        $this->assertEquals($obj2, $hashMap->get(2));
    }

    /**
     * Assert if can't put an empty key
     */
    public function testNullKey()
    {
        $this->expectException(EmptyKeyException::class);

        $hashMap = new LinkedMap();
        $hashMap->put(null, 1);
    }

    /**
     * Assert if can't put an empty key
     */
    public function testFalseKey()
    {
        $this->expectException(EmptyKeyException::class);

        $hashMap = new LinkedMap();
        $hashMap->put(false, 1);
    }

    /**
     * Test if the foreach works fine
     */
    public function testForeach()
    {
        $hashMap = new LinkedMap();

        $values = [
            ["a", "b"],
            [1, 2],
            [new \stdClass(), 'd'],
            [[123], ['abc']],
            [031, new \stdClass()],
            ["habalaba", [true]],
        ];

        foreach ($values as $value) {
            $hashMap->put($value[0], $value[1]);
        }

        $cont = 0;
        foreach ($hashMap as $key => $value) {
            $asserts = $values[$cont++];
            $this->assertEquals($asserts[0], $key);
            $this->assertEquals($asserts[1], $value);
        }
    }

    /**
     * Test if can a value be found without key
     */
    public function testFindKeyByValue()
    {
        $hashMap = new LinkedMap();

        $std1 = new \stdClass();
        $std1->a = true;

        $std2 = new \stdClass();
        $std2->b = false;

        $hashMap->put(1, $std1);

        $this->assertEquals(true, $hashMap->containsValue($std1));
        $this->assertEquals(false, $hashMap->containsValue($std2));
    }

    /**
     * Test if a value will overwritten
     */
    public function testOverwrite()
    {
        $hashMap = new LinkedMap();

        $std = new \stdClass();
        $std->a = true;

        $hashMap->put($std, 1);
        $this->assertEquals(1, $hashMap->get($std));

        $std->b = false;

        $hashMap->put($std, 2);
        $this->assertEquals(2, $hashMap->get($std));
        $this->assertEquals(1, $hashMap->size());
    }

    /**
     * Test if size method works fine
     */
    public function testSize()
    {
        $hashMap = new LinkedMap();

        $this->assertEquals(0, $hashMap->size());

        $hashMap->put(1, 1);
        $hashMap->put(2, 1);
        $hashMap->put(3, 1);
        $hashMap->put(4, 1);

        $this->assertEquals(4, $hashMap->size());

        $hashMap->remove(1);
        $hashMap->remove(3);

        $this->assertEquals(2, $hashMap->size());

        $hashMap->clear();

        $this->assertEquals(0, $hashMap->size());
    }

    /**
     * test if the method remove is right
     */
    public function testRemove()
    {
        $hashMap = new LinkedMap();

        $hashMap->put(1, "a");
        $hashMap->put(2, "b");
        $hashMap->put(3, "c");
        $hashMap->put(4, "d");

        $hashMap->remove(1);
        $hashMap->remove(3);

        $this->assertEquals(null, $hashMap->get(1));
        $this->assertEquals("b", $hashMap->get(2));
        $this->assertEquals(null, $hashMap->get(3));
        $this->assertEquals("d", $hashMap->get(4));
    }

    /**
     * Test if the first item has a correct behavior
     */
    public function testFirstItem()
    {
        $hashMap = new LinkedMap();

        $this->assertEquals(null, $hashMap->first());

        $hashMap->put(1, 2);
        $hashMap->put(2, 3);

        $this->assertEquals(2, $hashMap->first());

        $hashMap->remove(1);

        $this->assertEquals(3, $hashMap->first());

        $hashMap->remove(2);

        $this->assertEquals(null, $hashMap->first());
    }

    /**
     * Test if the last item has a correct behavior
     */
    public function testLastItem()
    {
        $hashMap = new LinkedMap();

        $this->assertEquals(null, $hashMap->last());

        $hashMap->put(1, 2);
        $hashMap->put(2, 3);

        $this->assertEquals(3, $hashMap->last());

        $hashMap->remove(2);

        $this->assertEquals(2, $hashMap->last());

        $hashMap->remove(1);

        $this->assertEquals(null, $hashMap->last());
    }

    /**
     * Test if the empty method works fine
     */
    public function testEmpty()
    {
        $hashMap = new LinkedMap();

        $this->assertEquals(true, $hashMap->isEmpty());

        $hashMap->put(1, 1);

        $this->assertEquals(false, $hashMap->isEmpty());

        $hashMap->remove(1);

        $this->assertEquals(true, $hashMap->isEmpty());
    }

    /**
     * Test if the keys method works fine
     */
    public function testKeys()
    {
        $std = new \stdClass();

        $hashMap = new LinkedMap();
        $this->assertEquals([], $hashMap->keys());

        $hashMap->put(1, 1);
        $this->assertEquals([1], $hashMap->keys());

        $hashMap->put('abc', 2);
        $this->assertEquals([1, 'abc'], $hashMap->keys());

        $hashMap->put($std, 3);
        $this->assertEquals([1, 'abc', $std], $hashMap->keys());

        $hashMap->remove('abc');
        $this->assertEquals([1, $std], $hashMap->keys());

        $hashMap->remove(1);
        $this->assertEquals([$std], $hashMap->keys());
    }
}
