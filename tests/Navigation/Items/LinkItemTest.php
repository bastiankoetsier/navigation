<?php

use Bkoetsier\Navigation\Items\LinkItem;
use Mockery as m;

class LinkItemTest extends PHPUnit_Framework_TestCase{

	public function setUp()
	{

	}

	public function tearDown()
	{
		m::close();
	}

	protected function getItemMock()
	{
		return m::mock('\Bkoetsier\Navigation\Items\LinkItem');
	}

	public function testAddChild()
	{
		$item = new LinkItem('First','testuri',1);
		$child = $this->getItemMock();

		$child->shouldReceive('setParentId')->once();
		$child->shouldReceive('setLevel')->once();
		$child->shouldReceive('getParentId')->andReturn(1);

		$item->addChild($child);
		$this->assertTrue($item->hasChildren());
		$this->assertEquals($child->getParentId(),$item->getId());
	}


} 