<?php
use Bkoetsier\Navigation\Bucket;
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
		$bucket = new Bucket('test');
		$bucket->getItems();
	}

	public function testFindMethod()
	{
		$bucket = new Bucket('test');
		$item = $this->getItemMock();

		$item->shouldReceive('getId')->twice()->andReturn(uniqid());
		$item->shouldReceive('getLabel')->once()->andReturn('test');

		$bucket->add($item);
		$this->assertEquals($item,$bucket->find('test'));
	}

	/**
	 * @expectedException Bkoetsier\Navigation\Exceptions\ItemNotFoundException
	 */
	public function testFindReturnsFalse()
	{
		$bucket = new Bucket('test');
		$item = $this->getItemMock();

		$item->shouldReceive('getLabel')->once()->andReturn('foo');
		$item->shouldReceive('getId')->times(3)->andReturn('1');

		$bucket->add($item);
		$bucket->find('test');
	}

	public function testBucketCount()
	{
		$bucket = new Bucket('test');
		$item = $this->getItemMock();
		$item->shouldReceive('getId')->andReturn('1');
		$bucket->add($item);
		$this->assertCount(1,$bucket);
	}







}
 