<?php namespace Bkoetsier\Navigation\Test\Unit;

use Bkoetsier\Navigation\Bucket;
use Mockery as m;

class BucketTest extends \PHPUnit_Framework_TestCase
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
	    $itemId = uniqid();
		$item= $this->getItem();
		$c = $this->getCollection();
		$c->shouldReceive('all')->twice()->andReturn([$itemId => $item])->byDefault();
		$bucket = new Bucket($c);
		$this->assertSame($item,$bucket->findById($itemId));
		$this->assertFalse($bucket->findById('nonExistentItemId'));
	}

	/**
	 * @test
	 */
	public function it_can_find_items_by_label_and_returns_collection()
	{
	    $item = $this->getItem();
		$itemLabel = 'Item1';
		$c = $this->getCollection();
		$newCollection = $this->getCollection();
		$newCollection->shouldReceive('first')->once()->andReturn($item);
		$newCollection->shouldReceive('count')->once()->andReturn(0);
		$c->shouldReceive('filter')->withAnyArgs()->times(3)->andReturn($newCollection);
		$bucket = new Bucket($c);
		$this->assertsame($newCollection,$bucket->findByContent($itemLabel));
		$this->assertEquals($item,$bucket->findByContent($itemLabel)->first());
		$this->assertCount(0,$bucket->findByContent('nonExistentLabel'));
	}

	/**
	 * @test
	 */
	public function it_can_insert_children_to_existing_root()
	{
	    $rootId = uniqid();
		$childId = uniqid();

		$root = $this->getItem();
		$root->shouldReceive('getId')->twice()->andReturn($rootId);
		$root->shouldReceive('getLeft')->once()->andReturn(0);
		$root->shouldReceive('getRight')->times(3)->andReturn(1);
		$root->shouldReceive('setRight')->once()->with(3);
		$root->shouldReceive('getLevel')->once()->andReturn(0);

		$child = $this->getItem();
		$child->shouldReceive('setParent')->once()->with($rootId);
		$child->shouldReceive('getId')->once()->andReturn($childId);
		$child->shouldReceive('setLeft')->once()->with(1);
		$child->shouldReceive('setRight')->once()->with(2);
		$child->shouldReceive('setLevel')->once()->with(1);

		$c = $this->getCollection();
		$c->shouldReceive('put')->once()->with($rootId,$root);
		$c->shouldReceive('put')->once()->with($childId,$child);
		$c->shouldReceive('all')->once()->andReturn([
			$rootId => $root,
		]);

		$bucket = new Bucket($c);
		$bucket->add($root);
		$bucket->addChild($child,$root);
	}

	/**
	 * @test
	 */
	public function it_adds_a_new_root_item()
	{
	    $rootId = uniqid();
		$root = $this->getItem();
		$root->shouldReceive('getId')->once()->andReturn($rootId);
		$root->shouldReceive('getLeft')->once()->andReturn(0);
		$root->shouldReceive('getRight')->once()->andReturn(0);

		$root->shouldReceive('setLeft')->once()->with(0);
		$root->shouldReceive('setRight')->once()->with(1);

		$c = $this->getCollection();
		$c->shouldReceive('all')->twice()->andReturn([],[$rootId=>$root]);
		$c->shouldReceive('put')->once()->with($rootId,$root);

		$bucket = new Bucket($c);
		$bucket->addRoot($root);
	}
}
 