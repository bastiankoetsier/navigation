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
		$allItems = $this->collection->all();
		if(isset($allItems[$id]))
		{
			return $allItems[$id];
		}
		return false;
	}

	public function findByContent($content)
	{
		return $this->collection->filter(function($item)use($content){
			/**
			 * @var $item Item
			 */
			if($item->getContent() == $content){ return $item; }
			return false;
		});
	}

	public function pathItems($id)
	{
		$item = $this->findById($id);
		if( ! $item){ return new Collection; }
		return $this->getAncestorsAndSelf($item);
	}

	/**
	 * Hydrates the bucket with array of Objects
	 * @param $data \StdClass[]
	 * @param string $itemIdentifier Name of the identifier-property
	 * @param string $itemContent Name of the label-property
	 * @param string $parentIdentifier Name of the parent-identifier-property
	 * @return $this
	 */
	public function hydrate($data, $itemIdentifier='id', $itemContent='name',$parentIdentifier='parent')
	{
		foreach($data as $item)
		{
			$newItem = new Item($item->{$itemContent},$item->{$itemIdentifier});
			if ($item->{$parentIdentifier} == 0 || is_null($item->{$parentIdentifier}) )
			{
				$this->addRoot($newItem);
			}
			elseif ($parent = $this->findById($item->{$parentIdentifier}))
			{
				$this->addChild($newItem,$parent);
			}
		}
		return $this;
	}


    public function add(Item $item)
    {
       $this->collection->put($item->getId(),$item);
    }

    public function getCollection()
    {
        return $this->collection;
    }

	protected function getMax($column)
	{
		if( ! in_array(strtolower($column),['left','right']))
		{
			throw new \InvalidArgumentException("$column is not a valid Attribute");
		}
		$max = 0;
		$method = 'get'.ucfirst($column);
		foreach($this->getCollection()->all() as $item)
		{
			if($item->{$method}() > $max)
			{
				$max = $item->{$method}();
			}
		}
		return $max;
	}


	/**
	 * @param $filterFrom
	 * @return \Illuminate\Support\Collection
	 */
	protected function getFilterFrom($filterFrom)
	{
		return is_null($filterFrom) ? $this->collection : $filterFrom;
	}

    public function getMaxLeft()
    {
		return $this->getMax('left');
    }

	public function getMaxRight()
	{
		return $this->getMax('right');
	}

	public function hasChildren(Item $parent,Collection $filteredCollection = null)
	{
		return !!count($this->getChildrenWithoutSelf($parent,$filteredCollection));
	}

	public function getChildrenAndSelf(Item $parent,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($parent){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return ($item->getLeft() >=  $parent->getLeft()) && ($item->getRight() <= $parent->getRight());
		});
	}

	public function getChildrenWithoutSelf(Item $parent,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($parent){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return ($item->getLeft() >  $parent->getLeft()) && ($item->getRight() < $parent->getRight());
		});
	}

	public function getSiblingsAndSelf(Item $sibling,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($sibling){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return $item->getParent() == $sibling->getParent();
		});
	}

	public function getSiblingsWithoutSelf(Item $sibling,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($sibling){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return $item->getParent() == $sibling->getParent() && $item->getId() != $sibling->getId();
		});
	}

	public function getAncestorsAndSelf(Item $descendant,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($descendant){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return $item->getLeft() <= $descendant->getLeft() && $item->getRight() >= $descendant->getRight();
		});
	}

	public function getAncestorsWithoutSelf(Item $descendant,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($descendant){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return $item->getLeft() < $descendant->getLeft() && $item->getRight() > $descendant->getRight();
		});
	}

	public function getUntilMaxLevel($maxLevel,Collection $filteredCollection = null)
	{
		$filterFrom = $this->getFilterFrom($filteredCollection);
		return $filterFrom->filter(function($item)use($maxLevel){
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			return $item->getLevel() <= $maxLevel;
		});
	}

    public function addChild(Item $child, Item $parent)
    {
		$right = $parent->getRight();
	    $level = $parent->getLevel();
	    $child->setParent($parent->getId());
	    $child->setLevel($level + 1);
	    $child->setLeft($right);
	    $child->setRight($right+1);
	    foreach($this->getCollection()->all() as $item)
	    {
		    /**
		     * @var $item \Bkoetsier\Navigation\Items\Item
		     */
		    if($item->getLeft() >= $right)
		    {
			    $item->setLeft($item->getLeft() + 2);
		    }
		    if($item->getRight() >= $right)
		    {
			    $item->setRight($item->getRight() + 2);
		    }
	    }
	    $this->add($child);
    }

	public function addRoot(Item $item)
	{
		$left = ($this->getMaxRight() == 0) ? $this->getMaxRight() :  $this->getMaxRight() +1;
		$item->setLeft($left);
		$item->setRight($item->getLeft() + 1 );
		$this->add($item);
	}
}
