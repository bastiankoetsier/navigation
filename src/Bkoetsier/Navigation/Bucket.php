<?php namespace Bkoetsier\Navigation;

class Bucket {

	protected $items = [];
	protected $name;


	/**
	 * Adds ItemInterface item to $this->items (root)
	 * @param ItemInterface $item
	 * @return mixed
	 */
	public function add(ItemInterface $item)
	{
		$this->items[] = $item;
		return end($this->items);
	}

	/**
	 * @param string $label
	 * @param array $items
	 * @return ItemInterface|false
	 */
	public function find($label,$items = null)
	{
		if(is_null($items)) $items = $this->items;
		foreach($items as $item)
		{
			if($item->getLabel() == $label || $item->getId() == $label)
			{
				return $item;
			}
			elseif($item->hasChildren())
			{
				$found =  $this->find($label,$item->getChildren());
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
	 * @return array of ItemInterface
	 */
	public function pathItems($label)
	{
		$pathItems = [];
		$item = $this->find($label);
		$pathItems[] = $item;
		while( $item->getParent() )
		{
			$parentId = $item->getParent();
			$item = $this->find($parentId);
			$pathItems[] = $item;
		}
		return $pathItems;
	}

	public function hydrate($data,$identifier='id',$parentIdField='parentId',$type='link')
	{
		foreach($data as $item)
		{
			switch($type)
			{
				case 'link':    $newItem = new LinkItem($item->id, $item->name, $item->slug);
					break;
				default :       $newItem = new RawItem($item->id,$item->name);
			}
			if ($item->parent === 0)
			{
				$this->add($newItem);
			}
			elseif ($parent = $this->find($item->parent))
			{
				$parent->addChild($newItem);
			}
			else
			{
				dd($item);
			}
		}
		return $this;
	}
}