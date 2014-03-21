<?php namespace Bkoetsier\Navigation\Items;


class LinkItem extends Item{

	protected $uri;
	public function __construct($id,$label, $uri)
	{
		parent::__construct($id,$label);
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
}