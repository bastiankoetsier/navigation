<?php namespace Bkoetsier\Navigation\Test\Integration;

use Bkoetsier\Navigation\Bucket;
use Bkoetsier\Navigation\Items\Item;
use Illuminate\Support\Collection;

class BucketTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return Collection
	 */
	protected function getCollection()
	{
		$collection = new Collection($this->getFixtures());
		return $collection;
	}

	protected function getFixtures()
	{
		return [
			1 => new Item('Clothing',1,0,19),
			2 => new Item('Dresses',2,1,6,1),
			3 => new Item('Cocktail dresses',3,2,3,2),
			4 => new Item('Jersey Dresses',4,4,5,2),
			5 => new Item('Jeans',5,7,12,1),
			6 => new Item('Bootcut',6,8,9,5),
			7 => new Item('Slim fit',7,10,11,5),
			8 => new Item('Skirts',8,13,18,1),
			9 => new Item('Mini skirts',9,14,15,8),
			10 => new Item('Pencil skirts',10,16,17,8),
		];
	}
	/**
	 * @test
	 */
	public function it_adds_root_items_and_sets_correct_left_and_right()
	{
		$root1 = new Item('Dresses');
		$root2 = new Item('Jeans');
		$collection = new Collection();
		$bucket = new Bucket($collection);

		$bucket->addRoot($root1);
		$bucket->addRoot($root2);

		$this->assertEquals($root1,$bucket->getCollection()->first());
		$this->assertEquals(1,$bucket->getCollection()->first()->getRight());
		$this->assertEquals($root2,$bucket->getCollection()->slice(1,1,true)->first());
		$this->assertEquals(2,$bucket->getCollection()->slice(1,1,true)->first()->getRight());

	}

	/**
	 * @test
	 */
	public function it_adds_child_and_sets_correct_parent()
	{
		$root = new Item('Dresses');
		$child = new Item('Cocktail dresses');

		$collection = new Collection();
		$bucket = new Bucket($collection);

		$bucket->addRoot($root);
		$bucket->addChild($child,$root);

		$this->assertEquals($child,$bucket->getCollection()->slice(1,1,true)->first());
		$this->assertEquals($root->getId(),$bucket->getCollection()->slice(1,1,true)->first()->getParent());
	}
	

	/**
	 * @test
	 */
	public function it_returns_all_children_and_self()
	{
		$collection = $this->getCollection();
		$parent = $collection->all()[2];
		$bucket = new Bucket($collection);
		$this->assertEquals($bucket->getCollection()->slice(1,3,true),$bucket->getChildrenAndSelf($parent));
	}

	/**
	 * @test
	 */
	public function it_returns_all_children_but_self()
	{
		$collection = $this->getCollection();
		$parent = $collection->all()[2];
		$bucket = new Bucket($collection);
		$this->assertEquals($bucket->getCollection()->slice(2,2,true),$bucket->getChildrenWithoutSelf($parent));
	}
	
	/**
	 * @test
	 */
	public function it_returns_all_siblings_and_self()
	{
		$collection = $this->getCollection();
		$item = $collection->all()[3];
		$bucket = new Bucket($collection);
		$this->assertEquals($bucket->getCollection()->slice(2,2,true),$bucket->getSiblingsAndSelf($item));
	}

	/**
	 * @test
	 */
	public function it_returns_all_siblings_but_self()
	{
		$collection = $this->getCollection();
		$item = $collection->all()[3];
		$bucket = new Bucket($collection);
		$this->assertEquals($bucket->getCollection()->slice(3,1,true),$bucket->getSiblingsWithoutSelf($item));
	}

	/**
	 * @test
	 */
	public function it_returns_all_ancestors_and_self()
	{
		$collection = $this->getCollection();
		$item = $collection->all()[3];
		$bucket = new Bucket($collection);
		$this->assertEquals($bucket->getCollection()->slice(0,3,true),$bucket->getAncestorsAndSelf($item));
	}

	/**
	 * @test
	 */
	public function it_returns_all_ancestors_but_self()
	{
		$collection = $this->getCollection();
		$item = $collection->all()[3];
		$bucket = new Bucket($collection);
		$this->assertEquals($bucket->getCollection()->slice(0,2,true),$bucket->getAncestorsWithoutSelf($item));
	}



}
 