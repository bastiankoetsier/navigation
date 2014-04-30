<?php namespace Bkoetsier\Navigation\Items;

class Item
{
	protected $id;
	protected $label;
	protected $left;
	protected $right;
	protected $parent;


	public function __construct($label, $id = null, $left = null, $right = null, $parent = null)
	{
		if(is_null($id))
		{
			$this->setDefaultId();
		}
		if(is_null($left) || is_null($right))
		{
			$this->setDefaultLeftAndRight();
		}
		else
		{
			$this->setLeft($left);
			$this->setRight($right);
		}
		$this->setLabel($label);
		$this->setParent($parent);
	}

	public function isRoot()
	{
		return is_null($this->getParent());
	}

	public function setLeft($left)
    {
	    if( filter_var($left,FILTER_VALIDATE_INT) === false)
	    {
		    throw new \InvalidArgumentException('`left` is no integer');
	    }
		$this->left = $left;
    }

	public function setRight($right)
    {
	    if( filter_var($right,FILTER_VALIDATE_INT) === false)
	    {
		    throw new \InvalidArgumentException('`right` is no integer');
	    }
        $this->right = $right;
    }

	public function setParent($parent)
	{
		if( !is_null($parent) && filter_var($parent,FILTER_VALIDATE_INT) === false)
		{
			throw new \InvalidArgumentException('`parent` is not null or integer');
		}
		$this->parent = $parent;
	}

	public function getLeft()
    {
        return $this->left;
    }

	public function getRight()
    {
        return $this->right;
    }

	public function getParent()
    {
        return $this->parent;
    }

	public function setId($id)
    {
       $this->id = $id;
    }

	public function getId()
    {
        return $this->id;
    }

    public function setLabel($label)
    {
		$this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    protected function setDefaultLeftAndRight()
    {
		$this->setLeft(0);
	    $this->setRight(0);
    }

	protected function setDefaultId()
	{
		$this->setId(uniqid());
	}
}
