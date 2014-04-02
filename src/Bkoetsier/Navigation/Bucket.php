<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Exceptions\BucketEmptyException;
use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Items\LinkItem;

class Bucket  implements \IteratorAggregate, \Countable{

	protected $items = [];
	protected $name;

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

	public function getItems()
	{
		if(!count($this->items)) { throw new BucketEmptyException('Bucket must be hydrated / filled '); }
		return $this->items;
	}

	/**
	 * Searches for $label in $items (id & label)
	 * @param string $label
	 * @param array $items |null
	 * @return ItemInterface | false
	 */
	public function find($label,$items = null)
	{
		if(is_null($items)) { $items = $this->getItems(); }
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
				$found = $this->find($label,$item->getChildren());
				if($found)
				{
					return $found;
				}
			}
		}
		return false;
	}

	/**
	 * Searches for $label in $this->items and recall path to it all the way up
	 * @param $label
	 * @return ItemInterface[] | array()
	 */
	public function pathItems($label)
	{
		$pathItems = [];
		$item = $this->find($label);
		if( ! $item){ return []; }
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

	/**
	 * Hydrates the bucket with array of Objects
	 * @param $data \StdClass[]
	 * @param string $itemIdentifier Name of the identifier-property
	 * @param string $itemLabel Name of the label-property
	 * @param string $parentIdentifier Name of the parent-identifier-property
	 * @param string $uriField Name of uri-property
	 * @return $this
	 */
	public function hydrate($data, $itemIdentifier='id', $itemLabel='name',$parentIdentifier='parent',$uriField = 'slug')
	{
		foreach($data as $item)
		{
			$newItem = new LinkItem($item->{$itemLabel}, $item->{$uriField},$item->{$itemIdentifier});
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
		return count($this->items);
	}

}