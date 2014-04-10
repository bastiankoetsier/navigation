<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Exceptions\BucketEmptyException;
use Bkoetsier\Navigation\Renderer\ListRenderer;

class Navigation {

	protected $bucket = null;
	protected $breadcrumbs = null;
	protected $menus = [];

	/**
	 * Returns new Menu with Bucket
	 * @param $menuName
	 * @throws Exceptions\BucketEmptyException
	 * @return Menu
	 */
	public function menu($menuName)
	{
		if(isset($this->menus[$menuName]))
		{
			return $this->menus[$menuName];
		}
		if( ! $this->findBucket()->isFilled())
		{
			throw new BucketEmptyException('Bucket must be hydrated / filled ');
		}
		return $this->menus[$menuName] = new Menu($this->findBucket(),new ListRenderer);
	}

	/**
	 * Returns new Breadcrumb with Bucket
	 * @throws Exceptions\BucketEmptyException
	 * @return Breadcrumbs
	 */
	public function breadcrumbs()
	{
		if( ! is_null($this->breadcrumbs))
		{
			return $this->breadcrumbs;
		}
		if( ! $this->findBucket()->isFilled())
		{
			throw new BucketEmptyException('Bucket must be hydrated / filled ');
		}
		return $this->breadcrumbs = new Breadcrumbs($this->findBucket(),new ListRenderer);
	}

	public function bucket()
	{
		return $this->findBucket();
	}

	/**
	 * Searches all registered buckets for $bucketName
	 * if it doesn´t exist it´ll create /register it
	 * @return Bucket
	 */
	protected function findBucket()
	{
		if( is_null($this->getBucket()))
		{
			return $this->newBucket();
		}
		return $this->getBucket();
	}

	/**
	 * Creates new Bucket
	 * @return Bucket
	 */
	protected function newBucket()
	{
		$this->$bucket = new Bucket();

	}

	public function getBucket()
	{
		return $this->bucket;
	}
}