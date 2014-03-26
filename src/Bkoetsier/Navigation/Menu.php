<?php namespace Bkoetsier\Navigation;


use Bkoetsier\Navigation\Items\ItemInterface;

class Menu {

	protected $bucket = null;

	function __construct(Bucket $bucket)
	{
		$this->bucket = $bucket;
	}

	public function getBucket()
	{
		return $this->bucket;
	}

	public function fill()
	{
		call_user_func_array([$this->getBucket(),'hydrate'],func_get_args());
	}

	public function add(ItemInterface $item)
	{
		$this->bucket->add($item);
	}

	public function subNav($parentLabel,$maxLevel = Bucket::MAX_LEVEL)
	{
		$parent = $this->bucket->find($parentLabel);
		$children = $this->bucket->getChildren($parentLabel,$maxLevel);
		return array_merge([$parent],$children);
	}

}