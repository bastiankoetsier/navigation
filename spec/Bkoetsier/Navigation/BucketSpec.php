<?php

namespace spec\Bkoetsier\Navigation;

use Bkoetsier\Navigation\Items\ItemInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BucketSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Bkoetsier\Navigation\Bucket');
    }

	function it_should_only_add_items_to_collection(ItemInterface $item)
	{
		$this->push($item);
		$this->first()->shouldReturn($item);
	}

	function it_should_find_item_by_identifier(ItemInterface $item)
	{
		$id = md5('item1');
		$item->getId()->willReturn($id);
		$this->push($item);
		$this->findById($id)->shouldReturn($item);
	}

	function it_should_return_false_if_item_could_not_be_found_by_identifier(ItemInterface $item)
	{
		$itemId = 'lorem';
		$item->getId()->willReturn($itemId);
		$this->push($item);
		$searchId = 'item1';
		$this->findById($searchId)->shouldReturn(false);
	}




}
