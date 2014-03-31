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
		$item = new Item('First',1);
		$child = $this->getItemMock();

		$child->shouldReceive('getId')->once()->andReturn('2');
		$child->shouldReceive('setParentId')->with($item->getId())->once();
		$child->shouldReceive('setLevel')->once();
		$child->shouldReceive('getParentId')->andReturn(1);

		$item->addChild($child);
		$this->assertTrue($item->hasChildren());
		$this->assertEquals($child->getParentId(),$item->getId());
	}


} 