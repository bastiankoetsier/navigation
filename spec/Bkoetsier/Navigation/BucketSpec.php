<?php

namespace spec\Bkoetsier\Navigation;

use Bkoetsier\Navigation\Items\Item;
use Illuminate\Support\Collection;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BucketSpec extends ObjectBehavior
{

	function it_should_be_initialisable(Collection $collection)
	{
		$this->beConstructedWith($collection);
		$this->shouldHaveType('Bkoetsier\Navigation\Bucket');
	}

	function it_returns_the_collection_instance(Collection $collection)
	{
		$this->beConstructedWith($collection);
		$this->getCollection()->shouldHaveType('Illuminate\Support\Collection');
	}

	function it_can_add_items_to_the_collection_and_return_them(Collection $collection,Item $item)
	{
		$item->getId()->willReturn('item1');
		$collection->put('item1',$item)->shouldBeCalled();
		$collection->first()->willReturn($item);
		$this->beConstructedWith($collection);
		$this->add($item);
		$this->getCollection()->first()->shouldBe($item);
	}

	function it_can_find_items_by_the_id(Collection $collection,Item $item1,Item $item2)
	{
		$itemId1 = uniqid();
		$itemId2 = uniqid();
		$item1->getId()->willReturn($itemId1);
		$item2->getId()->willReturn($itemId2);
		$collection->put($itemId1,$item1)->shouldBeCalled();
		$collection->put($itemId2,$item2)->shouldBeCalled();
		$collection->all()->willReturn([
				$itemId1 => $item1,
				$itemId2 => $item2,
		]);
		$this->beConstructedWith($collection);

		$this->add($item1);
		$this->add($item2);

		$this->findById($itemId1)->shouldBe($item1);
		$this->findById('non_existing_item')->shouldBe(false);
	}

	function it_can_get_max_left_and_right_values(Collection $collection,Item $item1)
	{
		$itemId = uniqid();
		$item1->getId()->willReturn($itemId);
		$item1->getLeft()->willReturn(5);
		$item1->getRight()->willReturn(3);

		$collection->put($itemId,$item1)->shouldBeCalled();
		$collection->all()->willReturn([
			$itemId => $item1,
		]);
		$this->beConstructedWith($collection);
		$this->add($item1);

		$this->getMaxLeft()->shouldBe(5);
		$this->getMaxRight()->shouldBe(3);
	}



	function it_can_insert_children_to_existing_root_items(Collection $collection,Item $root,Item $child)
	{
		$rootId = uniqid();
		$childId = uniqid();

		$root->getId()->willReturn($rootId);
		$root->getLeft()->willReturn(0);
		$root->getRight()->willReturn(1);

		$child->getId()->willReturn($childId);
		$child->setLeft(1)->shouldBeCalled();
		$child->setRight(2)->shouldBeCalled();

		$collection->put($rootId,$root)->shouldBeCalled();
		$collection->put($childId,$child)->shouldBeCalled();


		$collection->all()->willReturn([
			$rootId => $root,
		]);
		$this->beConstructedWith($collection);
		$this->addChild($child,$root);

	}


}
