<?php
use matchdav\Fp\Configurable;
use matchdav\Fp\Functional;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    /**
     * @return FunctionalTest
     */
    public function __construct()
    {
        $this->square = function ($num) {
            return pow($num, 2);
        };

        $this->double = function ($num) {
            return $num * 2;
        };
        $this->add = function ($a, $b) {
            return $a + $b;
        };

        $this->concat = function ($a, $b) {
            return $a . $b;
        };
        $this->concatThree = function ($a, $b, $c) {
            return $a . $b . $c;
        };

        $this->addThree = function ($a, $b, $c) {
            return $a + $b + $c;
        };
    }
    public function testFlow()
    {
        $squareThenDouble = Functional::flow($this->square, $this->double);
        $doubleThenSquare = Functional::flow($this->double, $this->square);
        $this->assertEquals($squareThenDouble(3), 18);
        $this->assertEquals($doubleThenSquare(3), 36);
    }
    public function testArity()
    {
        $this->assertEquals(Functional::arity('strtoupper'), 1);
        $this->assertEquals(Functional::arity('array_map'), 2);
        $this->assertEquals(Functional::arity(function () {}), 0);
        $this->assertEquals(Functional::arity(function ($a) {}), 1);
    }
    public function testPartial()
    {
        $add = Functional::partial($this->add, 1);
        $concat = Functional::partial($this->concat, 1);
        $concatRight = Functional::partialRight($this->concat, 1);
        $this->assertTrue(is_callable($add));
        $this->assertTrue(is_callable($concat));
        $this->assertTrue(is_callable($concatRight));
        $this->assertEquals($add(1), 2);
        $this->assertEquals($concat(2), '12');
        $this->assertEquals($concatRight(2), '21');
    }
    public function testCurry()
    {
        $add = Functional::curry($this->addThree);
        $concat = Functional::curry($this->concatThree);
        $this->assertTrue(is_callable($add));
        $this->assertTrue(is_callable($add(1)));
        $this->assertTrue(is_callable($add(1)(1)));
        $this->assertEquals($add(1)(1)(1), 3);
    }
    public function testInvoke()
    {
        $a = new Configurable;
        $a->set('name', 'dave');
        $this->assertEquals(Functional::invoke($a, 'get', 'name'), 'dave');
        $this->assertEquals(Functional::invoke($a, 'props', 'name'), ['name' => 'dave']);
        $a->set('foo', 'bar');
        $this->assertEquals(Functional::invoke($a, 'props', 'name foo'), ['name' => 'dave', 'foo' => 'bar']);
    }
}
