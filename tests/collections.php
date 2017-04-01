<?php

use matchdav\Fp\Collections;
use matchdav\Fp\Configurable;
use PHPUnit\Framework\TestCase;

class CollectionsTest extends TestCase
{
    public function testMap()
    {
        $a = new Configurable();
        $this->assertEquals(sizeof(Collections::map($a, 'strtoupper')), 0);
        $a->set('name', 'dave');
        $this->assertEquals(sizeof(Collections::map($a, 'strtoupper')), 1);
        $this->assertEquals(Collections::first(Collections::map($a, 'strtoupper')), 'DAVE');
        $coll = ['dog'];
        $this->assertEquals(Collections::first(Collections::map($coll, 'strtoupper')), 'DOG');
    }
}
