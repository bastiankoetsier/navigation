<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Items\Item;
use Illuminate\Support\Collection;

class Bucket {

	protected $collection;

	function __construct(Collection $collection)
	{
		$this->collection = $collection;
	}

	
	public function findById($id)
	{
		if(isset($this->collection->all()[$id]))
		{
			return $this->collection->all()[$id];
		}
		return false;
	}

	/**
	 * Searches for $label in $this->items and recall path to it all the way up
	 * @param $label
	 * @return ItemInterface[] | array()
	 */
	/*public function pathItems($label)
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
	}*/

	/**
	 * Hydrates the bucket with array of Objects
	 * @param $data \StdClass[]
	 * @param string $itemIdentifier Name of the identifier-property
	 * @param string $itemLabel Name of the label-property
	 * @param string $parentIdentifier Name of the parent-identifier-property
	 * @param string $uriField Name of uri-property
	 * @return $this
	 */
	/*public function hydrate($data, $itemIdentifier='id', $itemLabel='name',$parentIdentifier='parent',$uriField = 'slug')
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
	}*/


    public function add(Item $item)
    {
       $this->collection->put($item->getId(),$item);
    }

    public function getCollection()
    {
        return $this->collection;
    }
}
