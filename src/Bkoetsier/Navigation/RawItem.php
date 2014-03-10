<?php namespace Bkoetsier\Navigation;

class RawItem extends Item{

	public function __construct($id,$label)
	{
		parent::__construct($id);
		$this->label = $label;
	}
} 