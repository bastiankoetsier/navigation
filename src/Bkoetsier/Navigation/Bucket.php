<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Exceptions\BucketEmptyException;
use Bkoetsier\Navigation\Exceptions\ItemNotFoundException;
use Bkoetsier\Navigation\Items\Item;
use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Items\LinkItem;

class Bucket  implements \IteratorAggregate, \Countable{

	protected $items = [];
	protected $name;

	const MAX_LEVEL = 3;

	function __construct($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	/**
	 * Adds ItemInterface item to $this->items (root)
	 * @param ItemInterface $item
	 * @return ItemInterface
	 */
	public function add(ItemInterface $item)
	{
		$this->items[$item->getId()] = $item;
		return $this->items[$item->getId()];
	}

	public function clear()
	{
		$this->items = [];
	}

	public function getItems()
	{
		if(!count($this->items)) { throw new BucketEmptyException('Bucket must be hydrated / filled '); }
		return $this->items;
	}

	public function getChildren($parentLabel,$maxLevel= self::MAX_LEVEL)
	{
		$parent = $this->find($parentLabel);
		if( ! $parent->hasChildren() || $parent->getLevel() == $maxLevel) { return false; }
		$children = [];

		foreach($parent->getChildren() as $childId)
		{
			$child = $this->find($childId);
			if($child->getLevel() < $maxLevel)
			{
				if($child->hasChildren())
				{
					$tmp = $this->getChildren($child->getLabel(),$maxLevel);
					$children[] = [$child,$tmp];
				}
				else
				{
					$children[] = [$child];
				}
			}
		}
		return $children;
	}

	/**
	 * @param string $label
	 * @param array $items |null
	 * @throws Exceptions\ItemNotFoundException
	 * @return ItemInterface
	 */
	public function find($label,$items = null)
	{
		if(is_null($items)) { $items = $this->getItems(); }
		if(isset($this->items[$label])) { return $this->items[$label]; }
		foreach($items as $item)
		{
			/**
			 * @var $item ItemInterface
			 */
			if($item->getLabel() == $label || $item->getId() == $label)
			{
				return $item;
			}
		}
		throw new ItemNotFoundException;
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
		$pathItems[] = $item;
		while( $item->getParentId() )
		{
			$parentId = $item->getParentId();
			$item = $this->find($parentId);
			$pathItems[] = $item;
		}
		krsort($pathItems);
		return $pathItems;
	}

	public function hydrate($data, $itemIdentifier='id', $itemLabel='name',$parentIdentifier='parent', $type='link')
	{
		foreach($data as $item)
		{
			switch($type)
			{
				case 'link':
					$newItem = new LinkItem($item->{$itemLabel}, $item->slug,$item->{$itemIdentifier});
					break;
				default:
					$newItem = new Item($item->{$itemLabel},$item->{$itemIdentifier});
			}
			if ($item->{$parentIdentifier} == 0 || is_null($item->{$parentIdentifier}) )
			{
				$this->add($newItem);
			}
			elseif ($parent = $this->find($item->{$parentIdentifier}))
			{
				$parent->addChild($newItem);
				$this->add($newItem);
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
		return count($this->items);
	}

}