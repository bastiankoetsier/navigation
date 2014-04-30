<?php

use Bkoetsier\Navigation\Bucket;
use Mockery as m;

class BucketTest extends PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		m::close();
	}

	protected function getCollection()
	{
		return m::mock('Illuminate\Support\Collection');
	}

	protected function getItem()
	{
		return m::mock('Bkoetsier\Navigation\Items\Item');
	}

	/**
	 * @test
	 */
	public function it_returns_the_collection_instance()
	{
	    $c = $this->getCollection();
		$bucket = new Bucket($c);
		$this->assertSame($c,$bucket->getCollection());
	}

	/**
	 * @test
	 */
	public function it_can_add_items_to_the_collection()
	{
		$itemId = uniqid();
		$item = $this->getItem();
		$item->shouldReceive('getId')->andReturn($itemId);
	    $c = $this->getCollection();
		$c->shouldReceive('put')->with($itemId,$item)->once();
		$c->shouldReceive('count')->andReturn(1);
		$bucket = new Bucket($c);
		$bucket->add($item);
		$this->assertCount(1,$bucket->getCollection());
	}

	/**
	 * @test
	 */
	public function it_can_find_items_by_id()
	{
	    $itemId1 = uniqid();
		$item1= $this->getItem();
		$item1->shouldReceive('getId')->andReturn($itemId1);
		$itemId2 = uniqid();
		$item2= $this->getItem();
		$item2->shouldReceive('getId')->andReturn($itemId2);

	}

	/**
	 * @test
	 */
	public function it_can_insert_children_to_existing_root()
	{
	    $rootId = uniqid();
		$childId = uniqid();

		$root = $this->getItem();
		$root->shouldReceive('getId')->andReturn($rootId);
		$root->shouldReceive('getLeft')->andReturn(0);
		$root->shouldReceive('getRight')->andReturn(1);
		$root->shouldReceive('setRight')->with(3)->once();

		$child = $this->getItem();
		$child->shouldReceive('setParent')->with($rootId)->once();
		$child->shouldReceive('getId')->andReturn($childId);
		$child->shouldReceive('setLeft')->with(1)->once();
		$child->shouldReceive('setRight')->with(2)->once();

		$c = $this->getCollection();
		$c->shouldReceive('put')->with($rootId,$root)->once();
		$c->shouldReceive('put')->with($childId,$child)->once();
		$c->shouldReceive('all')->andReturn([
			$rootId => $root,
		]);

		$bucket = new Bucket($c);
		$bucket->add($root);
		$bucket->addChild($child,$root);
	}

	/**
	 * @test
	 */
	public function it_returns_one_during_getMaxRight_if_no_items_are_defined()
	{
	    $c = $this->getCollection();
		$c->shouldReceive('all')->andReturn([]);
		$bucket = new Bucket($c);
		$this->assertEquals(1,$bucket->getMaxRight());
	}

	/**
	 * @test
	 */
	public function it_adds_items_to_root_with_correct_right_and_left()
	{
	    $rootId = uniqid();
		$root = $this->getItem();
		$root->shouldReceive('getId')->andReturn($rootId);
		$root->shouldReceive('getLeft')->andReturn(0);
		$root->shouldReceive('getRight')->andReturn(0)->byDefault();
		$root->shouldReceive('getRight')->andReturn(1);
		$root->shouldReceive('setRight')->with(1)->once();

		$c = $this->getCollection();
		$c->shouldReceive('all')->andReturn([],[$rootId=>$root]);
		$c->shouldReceive('put')->with($rootId,$root)->once();
		$bucket = new Bucket($c);

		$bucket->addRoot($root);
		$this->assertEquals(1,$root->getRight());
	}




}
 