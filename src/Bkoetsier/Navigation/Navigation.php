<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Renderer\ListRenderer;

class Navigation {

	protected $buckets = [];

	/**
	 * Returns new Menu with Bucket
	 * @param $name
	 * @return Menu
	 */
	public function menu($name)
	{
		return new Menu($this->findBucket($name),new ListRenderer);
	}

	/**
	 * Returns new Breadcrumb with Bucket
	 * @param $name
	 * @return Breadcrumbs
	 */
	public function breadcrumbs($name)
	{
		return new Breadcrumbs($this->findBucket($name),new ListRenderer);
	}

	/**
	 * Returns all registered Buckets
	 * @return array
	 */
	public function getBuckets()
	{
		return $this->buckets;
	}

	/**
	 * Searches all registered buckets for $bucketName
	 * if it doesn´t exist it´ll create /register it
	 * @param $bucketName
	 * @return Bucket
	 */
	protected function findBucket($bucketName)
	{
		foreach($this->getBuckets() as $bucket)
		{
			/**
			 * @var $bucket Bucket
			 */
			if($bucket->getName() == $bucketName)
				return $bucket;
		}
		return $this->newBucket($bucketName);
	}

	/**
	 * Creates new Bucket
	 * @param $bucketName
	 * @return mixed
	 */
	protected function newBucket($bucketName)
	{
		$bucket = new Bucket($bucketName);
		return $this->addBucket($bucket);
	}

	/**
	 * Adds Bucket to bucket list
	 * @param Bucket $bucket
	 * @return mixed
	 */
	protected function addBucket(Bucket $bucket)
	{
		$this->buckets[] = $bucket;
		return end($this->buckets);
	}
}