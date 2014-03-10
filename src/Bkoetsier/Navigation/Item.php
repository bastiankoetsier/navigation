<?php namespace Bkoetsier\Navigation;

abstract class Item implements ItemInterface
{

	protected $id;
	protected $parent = null;
	protected $label;
	protected $children = [];

	public function __construct($id)
	{
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
	public function setParent($parentId)
	{
		$this->parent = $parentId;
	}
	/**
	 * adds new item to collection
	 * @param ItemInterface $item
	 * @return mixed
	 */
	public function addChild(ItemInterface $item)
	{
		$item->setParent($this->getId());
		$this->children[] = $item;
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
	public function getParent()
	{
		return $this->parent;
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
} 