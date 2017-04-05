<?php

use matchdav\Fp\Configurable;
use PHPUnit\Framework\TestCase;

class ConfigurableTest extends TestCase
{
    public function __construct()
    {
        $this->obj = new Configurable([
            'name' => 'dave',
            'cat' => 'felix',
        ]);

    }
    public function testGet()
    {
        $this->assertEquals($this->obj->get('name'), 'dave');
        $this->assertEquals($this->obj->get('cat'), 'felix');
    }
    public function testSet()
    {
        $this->obj->set('age', 42);
        $this->assertEquals($this->obj->get('age'), 42);
    }
    public function testIterator()
    {
        $data = [];
        foreach ($this->obj as $key => $value) {
            $data[$key] = $value;
        }
        $this->assertTrue(array_key_exists('name', $data));
    }
}
