<?php

use Bkoetsier\Navigation\Items\Item;
use Mockery as m;

class ItemTest extends PHPUnit_Framework_TestCase
{

	public function tearDown()
	{
		m::close();
	}

	/**
	 * @test
	 */
	public function it_gets_initialized_values()
	{
		$item = new Item('Item1',15,0,8,null);
		$this->assertEquals(15,$item->getId());
		$this->assertEquals(0,$item->getLeft());
		$this->assertEquals(8,$item->getRight());
		$this->assertEquals(null,$item->getParent());
	}

	/**
	 * @test
	 */
	public function it_sets_default_id_and_left_and_right_values_if_no_constructor_args_given()
	{
	    $item = new Item('Item1');
		$this->assertEquals(0,$item->getLeft());
		$this->assertEquals(0,$item->getRight());
		$this->assertNotNull($item->getId());
	}




}
 