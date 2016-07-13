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
     * @var LinkedMap
     */
    private $map;

    /**
     * @var \stdClass
     */
    private $someObject1;

    /**
     * @var \stdClass
     */
    private $someObject2;

    protected function setUp()
    {
        $this->map = new LinkedMap();

        $this->someObject1 = new \stdClass();
        $this->someObject1->a = true;

        $this->someObject2 = new \stdClass();
        $this->someObject2->b = false;

        parent::setUp();
    }

    public function testPutScalarWithSuccess()
    {
        $this->map->put(1, 2);
        $this->map->put("a", "b");
        $this->map->put(["023"], ["abc"]);

        $this->assertEquals(2, $this->map->get(1));
        $this->assertEquals("b", $this->map->get("a"));
        $this->assertEquals(["abc"], $this->map->get(["023"]));
    }

    public function testPutObjectWithSuccess()
    {
        $this->map->put($this->someObject1, 1);
        $this->map->put(2, $this->someObject2);

        $this->assertEquals(1, $this->map->get($this->someObject1));
        $this->assertEquals($this->someObject2, $this->map->get(2));
    }

    public function testNullKey()
    {
        $this->expectException(EmptyKeyException::class);
        $this->map->put(null, 1);
    }

    public function testFalseKey()
    {
        $this->expectException(EmptyKeyException::class);
        $this->map->put(false, 1);
    }

    public function testForeach()
    {
        $values = [
            ["a", "b"],
            [1, 2],
            [$this->someObject1, 'd'],
            [[123], ['abc']],
            [031, $this->someObject2],
            ["habalaba", [true]],
        ];

        foreach ($values as $value) {
            $this->map->put($value[0], $value[1]);
        }

        $cont = 0;
        foreach ($this->map as $key => $value) {
            $asserts = $values[$cont++];
            $this->assertEquals($asserts[0], $key);
            $this->assertEquals($asserts[1], $value);
        }
    }

    public function testFindKeyByValue()
    {
        $this->map->put(1, $this->someObject1);

        $this->assertTrue($this->map->containsValue($this->someObject1));
        $this->assertFalse($this->map->containsValue($this->someObject2));
    }

    public function testOverwrite()
    {
        $this->map->put($this->someObject1, 1);
        $this->assertEquals(1, $this->map->get($this->someObject1));

        $this->someObject1->b = false;

        $this->map->put($this->someObject1, 2);
        $this->assertEquals(2, $this->map->get($this->someObject1));
        $this->assertEquals(1, $this->map->size());
    }

    public function testSize()
    {
        $this->assertEquals(0, $this->map->size());

        $this->map->put(1, 1);
        $this->map->put(2, 1);
        $this->map->put(3, 1);
        $this->map->put(4, 1);
        $this->assertEquals(4, $this->map->size());

        $this->map->remove(1);
        $this->map->remove(3);
        $this->assertEquals(2, $this->map->size());

        $this->map->clear();
        $this->assertEquals(0, $this->map->size());
    }

    public function testRemove()
    {
        $this->map->put(1, "a");
        $this->map->put(2, "b");
        $this->map->put(3, "c");
        $this->map->put(4, "d");

        $this->map->remove(1);
        $this->map->remove(3);

        $this->assertNull($this->map->get(1));
        $this->assertEquals("b", $this->map->get(2));
        $this->assertNull($this->map->get(3));
        $this->assertEquals("d", $this->map->get(4));
    }

    public function testFirstItem()
    {
        $this->assertNull($this->map->first());

        $this->map->put(1, 2);
        $this->map->put(2, 3);
        $this->assertEquals(2, $this->map->first());

        $this->map->remove(1);
        $this->assertEquals(3, $this->map->first());

        $this->map->remove(2);
        $this->assertNull($this->map->first());
    }

    public function testLastItem()
    {
        $this->assertNull($this->map->last());

        $this->map->put(1, 2);
        $this->map->put(2, 3);
        $this->assertEquals(3, $this->map->last());

        $this->map->remove(2);
        $this->assertEquals(2, $this->map->last());

        $this->map->remove(1);
        $this->assertNull($this->map->last());
    }

    public function testEmpty()
    {
        $this->assertTrue($this->map->isEmpty());

        $this->map->put(1, 1);
        $this->assertFalse($this->map->isEmpty());

        $this->map->remove(1);
        $this->assertTrue($this->map->isEmpty());
    }

    public function testKeys()
    {
        $this->assertEquals([], $this->map->keys());

        $this->map->put(1, 1);
        $this->assertEquals([1], $this->map->keys());

        $this->map->put('abc', 2);
        $this->assertEquals([1, 'abc'], $this->map->keys());

        $this->map->put($this->someObject1, 3);
        $this->assertEquals([1, 'abc', $this->someObject1], $this->map->keys());

        $this->map->remove('abc');
        $this->assertEquals([1, $this->someObject1], $this->map->keys());

        $this->map->remove(1);
        $this->assertEquals([$this->someObject1], $this->map->keys());
    }
}
