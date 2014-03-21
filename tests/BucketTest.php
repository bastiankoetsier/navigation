<?php
use Bkoetsier\Navigation\Bucket;
use Bkoetsier\Navigation\Items\Item;
use Mockery as m;


/**
 * Created by PhpStorm.
 * User: bastian
 * Date: 3/21/14
 * Time: 2:55 PM
 */

class BucketTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{

	}

	public function tearDown()
	{
		m::close();
	}

	protected function getItemMock()
	{
		return $item = m::mock('Bkoetsier\Navigation\Items\ItemInterface');
	}

	/**
	 * @expectedException Bkoetsier\Navigation\Exceptions\BucketEmptyException
	 */
	public function testEmptyBucketThrowsException()
	{
		$bucket = new Bucket();
		$bucket->getItems();
	}

	public function testFindMethod()
	{
		$bucket = new Bucket();
		$item = $this->getItemMock();

		$item->shouldReceive('getLabel')->once()->andReturn('test');

		$bucket->add($item);
		$this->assertEquals($item,$bucket->find('test'));
	}

	public function testRecursiveFind()
	{
		$bucket = new Bucket();
		$parent = $this->getItemMock();
		$child = $this->getItemMock();

		$parent->shouldReceive('addChild')->once();
		$parent->shouldReceive('getLabel')->andReturn(false);
		$parent->shouldReceive('getId')->andReturn(false);
		$parent->shouldReceive('hasChildren')->andReturn(true);
		$parent->shouldReceive('getChildren')->andReturn($child);
		$child->shouldReceive('getLabel')->andReturn('child');



		$parent->addChild($child);
		$bucket->add($parent);

		$this->assertEquals($child,$bucket->find('child'));


	}

	public function testFindReturnsFalse()
	{
		$bucket = new Bucket();
		$item = $this->getItemMock();

		$item->shouldReceive('getLabel')->once()->andReturn('foo');
		$item->shouldReceive('getId')->once()->andReturn(uniqid());
		$item->shouldReceive('hasChildren')->once()->andReturn(false);

		$bucket->add($item);
		$this->assertFalse($bucket->find('test'));
	}

	public function testBucketCount()
	{
		$bucket = new Bucket();
		$item = $this->getItemMock();

		$item->shouldReceive('hasChildren')->once()->andReturn(false);

		$bucket->add($item);
		$this->assertCount(1,$bucket);
	}

	public function testBucketCountWithNestedItems()
	{
		$bucket = new Bucket();
		$item1 = m::mock('Bkoetsier\Navigation\Items\ItemInterface');
		$item2 = m::mock('Bkoetsier\Navigation\Items\ItemInterface');
	}






}
 