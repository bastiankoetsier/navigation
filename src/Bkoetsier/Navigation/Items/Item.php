<?php namespace Bkoetsier\Navigation\Items;

class Item
{
	protected $id;
	protected $content;
	protected $left;
	protected $right;
	protected $parent;
	protected $level;


	public function __construct($content, $id = null, $left = null, $right = null, $parent = null)
	{
		if(is_null($id))
		{
			$this->setDefaultId();
		}
		else
		{
			$this->setId($id);
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
		$this->setLevel(0);
		$this->setContent($content);
		$this->setParent($parent);
	}

	public function isRoot()
	{
		return is_null($this->getParent());
	}

	public function setLeft($left)
    {
		$this->left = $left;
    }

	public function setRight($right)
    {
        $this->right = $right;
    }

	public function setParent($parent)
	{
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

    public function setContent($content)
    {
		$this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
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

	/**
	 * @return mixed
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * @param int $level
	 */
	public function setLevel($level)
	{
		$this->level = $level;
	}
}
