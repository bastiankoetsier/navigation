<?php namespace Bkoetsier\Navigation;

class Breadcrumbs {

	protected $bucket = null;

	function __construct(Bucket $bucket)
	{
		$this->bucket = $bucket;
	}

	public function pathTo($label)
	{
		return $this->bucket->pathItems($label);
	}
} 