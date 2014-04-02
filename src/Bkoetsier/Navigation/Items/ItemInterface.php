<?php namespace Bkoetsier\Navigation\Items;

interface ItemInterface
{
	/**
	 * Returns parent-identifier for this item
	 * @return mixed
	 */
	public function getParentId();

	/**
	 * Gets unique(!) identifier
	 * @return mixed
	 */
	public function getId();

	/**
	 * Returns array of ItemInterface-items
	 * @return array
	 */
	public function getChildren();

	/**
	 * Sets parent-identifier
	 * @param $parentId
	 * @return mixed
	 */
	public function setParentId($parentId);

	/**
	 * @param ItemInterface $item
	 * @return mixed
	 */
	public function addChild(ItemInterface &$item);



	/**
	 * Returns true if item has children
	 * @return bool
	 */
	public function hasChildren();

	/**
	 *  Returns Label for item
	 * @return string
	 */
	public function getLabel();

	public function setLevel($level);

	public function getLevel();


}