<?php namespace Bkoetsier\Navigation\Items;


class LinkItem extends Item{

	protected $uri;
	protected $active = false;
	public function __construct($label,$uri,$id = null)
	{
		parent::__construct($label,$id);
		$this->setUri($uri);
	}

	public function getUri()
	{
		return $this->uri;
	}

	public function setUri($uri)
	{
		$this->uri = $uri;
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active;
	}

	public function setActive()
	{
		$this->active = ! $this->isActive();
	}


}