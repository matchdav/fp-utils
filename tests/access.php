<?php

use matchdav\Fp\Access;
use matchdav\Fp\Configurable;
use PHPUnit\Framework\TestCase;

class AccessTest extends TestCase
{
    public function __construct()
    {
        $this->nested = [
            'obj' => (object) [
                'a' => (object) [
                    'b' => 'hello world',
                ],
            ],
            'arr' => [
                'a' => [
                    'b' => 'hello other world',
                ],
            ],
            'mix' => (object) [
                'a' => [
                    'b' => 'hello mixed up world',
                ],
            ],
        ];

    }
    public function testGet()
    {
        $this->assertEquals(Access::get($this->nested['obj'], 'a.b'), 'hello world');
        $this->assertEquals(Access::get($this->nested['arr'], 'a.b'), 'hello other world');
        $this->assertEquals(Access::get($this->nested, 'mix.a.b'), 'hello mixed up world');
    }
    public function testSet()
    {
        $arr = [];
        $obj = new stdClass;
        Access::set($obj, 'human.name', 'dave');
        Access::set($obj, 'human.cat', 'sartre');
        Access::set($arr, 'human.name', 'dave');
        Access::set($arr, 'human.cat', 'sartre');
        $this->assertTrue(property_exists($obj, 'human'));
        $this->assertTrue(array_key_exists('human', $arr));
        $this->assertTrue(is_array($arr['human']));
        $this->assertTrue(is_object($obj->human));
        $this->assertEquals($obj->human->name, 'dave');
        $this->assertEquals($arr['human']['name'], 'dave');
        $this->assertEquals($arr['human']['cat'], 'sartre');
    }
    public function testHas()
    {
        $a = ['a' => ['b' => ['c' => null]]];
        $this->assertTrue(Access::has($a, 'a.b.c'));
        $this->assertFalse(Access::has($a, 'a.b.d'));
    }
    public function testResult()
    {
        $a = new Configurable((object) []);
        $a->set('name', 'dave');
        $json = $a->toJSON();
        $this->assertEquals(Access::result($a, 'toJSON'), $json);
        $this->assertEquals(Access::result($a, 'foo', 4), 4);
    }
}
