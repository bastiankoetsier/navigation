<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Exceptions\BucketEmptyException;
use Bkoetsier\Navigation\Exceptions\ItemNotFoundException;
use Bkoetsier\Navigation\Items\Item;
use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Items\LinkItem;

class Bucket  implements \IteratorAggregate, \Countable{

	protected $items = [];
	protected $name;

	/**
	 * Adds ItemInterface item to $this->items (root)
	 * @param ItemInterface $item
	 * @return ItemInterface
	 */
	public function add(ItemInterface $item)
	{
		$this->items[] = $item;
		return end($this->items);
	}

	public function getItems()
	{
		if(!count($this->items)) { throw new BucketEmptyException('Bucket must be hydrated / filled '); }
		return $this->items;
	}

	/**
	 * @param string $label
	 * @param array $items|null
	 * @return ItemInterface|false
	 */
	public function find($label,$items = null)
	{
		if(is_null($items)) $items = $this->getItems();
		foreach($items as $item)
		{
			/**
			 * @var $item ItemInterface
			 */
			if($item->getLabel() == $label || $item->getId() == $label)
			{
				return $item;
			}
			elseif($item->hasChildren())
			{
				$found =  $this->find($label,$item->getChildren());
				if($found){ return $found; }
			}
		}
		return false;
	}

	/**
	 * Searches for $label in $this->items and recall path to it all the way up
	 * @param $label
	 * @throws Exceptions\ItemNotFoundException
	 * @return array of ItemInterface
	 */
	public function pathItems($label)
	{
		$pathItems = [];
		$item = $this->find($label);
		if( ! $item) { throw new ItemNotFoundException(); }
		$pathItems[] = $item;
		while( $item->getParent() )
		{
			$parentId = $item->getParent();
			$item = $this->find($parentId);
			$pathItems[] = $item;
		}
		krsort($pathItems);
		return $pathItems;
	}

	public function hydrate($data, $itemIdentifier='id', $itemName='name',$parentIdentifier='parent', $type='link')
	{
		foreach($data as $item)
		{
			switch($type)
			{
				case 'link':
					$newItem = new LinkItem($item->{$itemIdentifier},utf8_decode($item->{$itemName}), $item->slug);
					break;
				default :
					$newItem = new Item($item->{$itemIdentifier},utf8_decode($item->{$itemName}));
			}
			if ($item->{$parentIdentifier} == 0 || is_null($item->{$parentIdentifier}) )
			{
				$this->add($newItem);
			}
			elseif ($parent = $this->find($item->{$parentIdentifier}))
			{
				$parent->addChild($newItem);
			}
		}
		return $this;
	}

	/**
	 * Retrieve an external iterator
	 * @return \Traversable An instance of an object implementing Iterator Traversable
	 */
	public function getIterator()
	{
		return new \RecursiveArrayIterator($this->items);
	}

	/**
	 * Count elements of an object
	 * @return int The custom count as an integer.
	 * The return value is cast to an integer.
	 */
	public function count()
	{
		return $this->countItems();
	}

	protected function countItems($items = null)
	{
		if(is_null($items))
		{
			$items = $this->getItems();
			$count = 0;
		}
		else
		{
			$count = 1;
		}
		foreach($items as $item)
		{
			/**
			 * @var $item ItemInterface
			 */
			if($item->hasChildren())
			{
				$count += $this->countItems($item->getChildren());
			}
			else
			{
				$count++;
			}
		}
		return $count;
	}
}