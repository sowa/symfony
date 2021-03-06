<?php
/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Tests\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\HeaderBag;

class HeaderBagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Symfony\Component\HttpFoundation\HeaderBag::__construct
     */
    public function testConstructor()
    {
        $bag = new HeaderBag(array('foo' => 'bar'));
        $this->assertTrue( $bag->has('foo'));
        try {
            $bag = new HeaderBag(array('foo' => 'bar'), 'nope');
            $this->assertFalse(TRUE,'nope is not a valid type'); // --> enfore request or response
        } catch ( \InvalidArgumentException $e) {
            // ignore
        }
        try {
            $bag = new HeaderBag(array('foo' => 'bar'), 'request');
        } catch ( \Exception $e) {
            $this->assertFalse(TRUE,'request should be a valid type'); // --> enforce request or response
        }
        try {
            $bag = new HeaderBag(array('foo' => 'bar'), 'response');
        } catch ( \Exception $e) {
            $this->assertFalse(TRUE,'response should be a valid type'); // --> enforce request or response
        }

    }

    /**
     * @covers Symfony\Component\HttpFoundation\HeaderBag::all
     */
    public function testAll()
    {
        $bag = new HeaderBag(array('foo' => 'bar'));
        $this->assertEquals(array('foo' => array('bar')), $bag->all(), '->all() gets all the input');

        $bag = new HeaderBag(array('FOO' => 'BAR'));
        $this->assertEquals(array('foo' => array('BAR')), $bag->all(), '->all() gets all the input key are lower case');
    }

    /**
     * @covers Symfony\Component\HttpFoundation\HeaderBag::replace
     */
    public function testReplace()
    {
        $bag = new HeaderBag(array('foo' => 'bar'));

        $bag->replace(array('NOPE' => 'BAR'));
        $this->assertEquals(array('nope' => array('BAR')), $bag->all(), '->replace() replaces the input with the argument');
        $this->assertFalse($bag->has('foo'), '->replace() overrides previously set the input');
    }


    /**
     * @covers Symfony\Component\HttpFoundation\HeaderBag::get
     */
    public function testGet()
    {
        $bag = new HeaderBag(array('foo' => 'bar', 'fuzz' => 'bizz'));
        $this->assertEquals( 'bar', $bag->get('foo'), '->get return current value');
        $this->assertEquals( 'bar', $bag->get('FoO'), '->get key in case insensitive');
        $this->assertEquals( array('bar'), $bag->get('foo', 'nope', false), '->get return the value as array');

        // defaults
        $this->assertNull($bag->get('none'), '->get unknown values returns null');
        $this->assertEquals( 'default', $bag->get('none', 'default'), '->get unknown values returns default');
        $this->assertEquals( array('default'), $bag->get('none', 'default', false), '->get unknown values returns default as array');

        $bag->set('foo', 'bor', false);
        $this->assertEquals( 'bar', $bag->get('foo'), '->get return first value');
        $this->assertEquals( array('bar', 'bor'), $bag->get('foo', 'nope', false), '->get return all values as array');
    }

    /**
     * @covers Symfony\Component\HttpFoundation\HeaderBag::contains
     */
    public function testContains()
    {
        $bag = new HeaderBag(array('foo' => 'bar', 'fuzz' => 'bizz'));
        $this->assertTrue(  $bag->contains('foo', 'bar'), '->contains first value');
        $this->assertTrue(  $bag->contains('fuzz', 'bizz'), '->contains second value');
        $this->assertFalse(  $bag->contains('nope', 'nope'), '->contains unknown value');
        $this->assertFalse(  $bag->contains('foo', 'nope'), '->contains unknown value');

        // Multiple values
        $bag->set('foo', 'bor', false);
        $this->assertTrue(  $bag->contains('foo', 'bar'), '->contains first value');
        $this->assertTrue(  $bag->contains('foo', 'bor'), '->contains second value');
        $this->assertFalse(  $bag->contains('foo', 'nope'), '->contains unknown value');
    }


}
