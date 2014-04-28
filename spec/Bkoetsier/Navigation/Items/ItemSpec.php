<?php

namespace spec\Bkoetsier\Navigation\Items;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ItemSpec extends ObjectBehavior
{
	function let()
	{
		$this->beConstructedWith('Item1',8,15,-1);
	}

	function it_gets_initialized_values()
	{
		$this->getLabel()->shouldBe('Item1');
		$this->getLeft()->shouldBe(8);
		$this->getRight()->shouldBe(15);
		$this->getParent()->shouldBe(-1);
		$this->shouldHaveType('Bkoetsier\Navigation\Items\Item');
	}

	function it_sets_the_id_label_left_right_and_parent_values()
	{
		$this->setId(md5('item1'));
		$this->setLabel('Item 1');
		$this->setLeft(5);
		$this->setRight(5);
		$this->setParent(5);

		$this->getId()->shouldBe(md5('item1'));
		$this->getLabel()->shouldBe('Item 1');
		$this->getLeft()->shouldBe(5);
		$this->getRight()->shouldBe(5);
		$this->getParent()->shouldBe(5);
	}

	function it_throws_exception_when_left_and_right()
	{
		$this->shouldThrow('InvalidArgumentException')->during('setLeft',array('hiThere'));
		$this->shouldThrow('InvalidArgumentException')->during('setRight',array('hiThere'));
	}

	function it_allows_nullable_parent_for_root_items()
	{
		$this->shouldThrow('InvalidArgumentException')->during('setParent',array('hiThere'));
		$this->shouldNotThrow('InvalidArgumentException')->during('setParent',array(null));
	}

	function it_returns_false_when_it_is_no_root_item()
	{
		$this->setParent(5);
		$this->shouldNotBeRoot();
		$this->setParent(null);
		$this->shouldBeRoot();
	}





}
