<?php


use Bkoetsier\Navigation\Items\Item;
use Mockery as m;

class ItemTest extends PHPUnit_Framework_TestCase{

	public function setUp()
	{

	}

	public function tearDown()
	{
		m::close();
	}

	protected function getItemMock()
	{
		return m::mock('\Bkoetsier\Navigation\Items\Item');
	}

	public function testAddChild()
	{
		$item = new Item(1,'First');
		$child = $this->getItemMock();

		$child->shouldReceive('setParent')->with($item->getId())->once();
		$child->shouldReceive('getParent')->andReturn(1);

		$item->addChild($child);
		$this->assertTrue($item->hasChildren());
		$this->assertEquals($child->getParent(),$item->getId());

	}


} 