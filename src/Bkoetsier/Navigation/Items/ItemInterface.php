<?php
namespace Bkoetsier\Navigation\Items;

interface ItemInterface
{
	/**
	 * @return mixed
	 */
	public function getLabel();

	/**
	 * @return int
	 */
	public function getLevel();

	/**
	 * @param mixed $label
	 */
	public function setLabel($label);

	/**
	 * @return null
	 */
	public function getId();

	/**
	 * @param null $id
	 */
	public function setId($id);

	/**
	 * @param mixed $parentId
	 * @return mixed|void
	 */
	public function setParentId($parentId);

	/**
	 * @param int $level
	 */
	public function setLevel($level);

	/**
	 * @return mixed
	 */
	public function getParentId();
}