<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Renderer\ListRenderer;
use Bkoetsier\Navigation\Renderer\RenderableInterface;

class Navigation {

	protected $buckets = [];
	protected $menus = [];
	protected $breadcrumbs = [];

	/**
	 * @var RenderableInterface | null
	 */
	protected $renderer = null;

	public function menu($name)
	{
		if(isset($this->menus[$name])) { return $this->menus[$name];}
		$this->menus[$name] = new Menu($this->handler($name),new ListRenderer);
		return end($this->menus);
	}

	public function breadcrumbs($name)
	{
		return new Breadcrumbs($this->handler($name),new ListRenderer);
	}

	public function setRenderer(RenderableInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	protected function handler($name)
	{
		return $this->findBucket($name);
	}

	protected function getBuckets()
	{
		return $this->buckets;
	}

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

	protected function newBucket($bucketName)
	{
		$bucket = new Bucket($bucketName);
		return $this->addBucket($bucket);
	}

	protected function addBucket(Bucket $bucket)
	{
		$this->buckets[] = $bucket;
		return end($this->buckets);
	}


	/*public function render($items,$maxDepth = 3)
	{
		if(is_null($this->renderer)) {throw new RendererMissingException('You must set the renderer-instance first'); }
		return $this->renderer->render($items,$maxDepth);
	}*/

} 