<?php namespace Bkoetsier\Navigation\Items;

class Item implements ItemInterface
{
	protected $id;
	protected $parentId = null;
	protected $label;
	protected $children = [];
	protected $level = 0;

	public function __construct($label,$id = null)
	{
		$this->setLabel($label);
		if(is_null($id))
			$this->id = uniqid();
		else
			$this->id = $id;
	}

	/**
	 * Gets unique(!) identifier
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Sets parent-identifier
	 * @param $parentId
	 * @return mixed
	 */
	public function setParentId($parentId)
	{
		$this->parentId = $parentId;
	}
	/**
	 * adds new item to collection
	 * @param ItemInterface $item
	 * @return mixed
	 */
	public function addChild(ItemInterface &$item)
	{
		$item->setParentId($this->getId());
		$item->setLevel($this->getLevel() + 1);
		$this->children[] = $item;
		return $this;
	}

	/**
	 * Returns true if item has children
	 * @return bool
	 */
	public function hasChildren()
	{
		return !!count($this->children);
	}

	/**
	 * Returns parent-identifier for this item
	 * @return mixed
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * Returns array of ItemInterface-items
	 * @return array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 *  Returns Label for item
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	public function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}

	public function setLevel($level)
	{
		$this->level = $level;
	}

	public function getLevel()
	{
		return $this->level;
	}

	public function __toString()
	{
		return $this->getLabel();
	}
}