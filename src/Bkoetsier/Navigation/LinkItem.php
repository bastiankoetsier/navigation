<?php namespace Bkoetsier\Navigation;

class LinkItem extends Item{

	protected $uri;
	public function __construct($id,$label, $uri)
	{
		parent::__construct($id);
		$this->label = $label;
		$this->uri = $uri;
	}

	public function getUri()
	{
		return $this->uri;
	}
}