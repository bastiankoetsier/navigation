<?php namespace Bkoetsier\Navigation\Items;

class Item implements ItemInterface
{
	protected $id;
	protected $label;
	protected $parentId = null;
	protected $level = 0;
	protected $left = 0;
	protected $right = 0;


	public function __construct($label,$id = null)
	{
	/*	$this->setLabel($label);
		if(is_null($id))
			$this->id = uniqid();
		else
			$this->id = $id;*/
	}


	public function isRoot()
	{
		return is_null($this->getParentId());
	}

	/**
	 * @param null $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return null
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $label
	 */
	public function setLabel($label)
	{
		$this->label = $label;
	}

	/**
	 * @return mixed
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param int $level
	 */
	public function setLevel($level)
	{
		if( ! filter_var($level,FILTER_VALIDATE_INT))
		{
			throw new \InvalidArgumentException('passed argument is no integer');
		}
		$this->level = $level;
	}

	/**
	 * @return int
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * @param mixed $parentId
	 */
	public function setParentId($parentId)
	{
		$this->parentId = $parentId;
	}

	/**
	 * @return mixed
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * @param int $left
	 * @throws \InvalidArgumentException
	 */
	public function setLeft($left)
	{
		if( ! filter_var($left,FILTER_VALIDATE_INT))
		{
			throw new \InvalidArgumentException('passed argument is no integer');
		}
		$this->left = $left;
	}

	/**
	 * @return int
	 */
	public function getLeft()
	{
		return $this->left;
	}

	/**
	 * @param int $right
	 * @throws \InvalidArgumentException
	 */
	public function setRight($right)
	{
		if( ! filter_var($right,FILTER_VALIDATE_INT))
		{
			throw new \InvalidArgumentException('passed argument is no integer');
		}
		$this->right = $right;
	}

	/**
	 * @return int
	 */
	public function getRight()
	{
		return $this->right;
	}


}