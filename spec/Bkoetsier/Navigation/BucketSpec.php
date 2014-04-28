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




}
