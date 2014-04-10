<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Renderer\BreadcrumbRendererInterface;

class Breadcrumbs {

	/**
	 * @var Bucket
	 */
	protected $bucket;
	/**
	 * @var ItemInterface[]
	 */
	protected $pathItems;
	/**
	 * @var Renderer\BreadcrumbRendererInterface
	 */
	protected $renderer;

	function __construct(Bucket $bucket,BreadcrumbRendererInterface $renderer)
	{
		$this->bucket = $bucket;
		$this->renderer = $renderer;
	}

	/**
	 * Retrieves all Items till $label
	 * and sets them as $pathItems for rendering
	 * @param $label
	 * @return $this
	 */
	public function pathTo($label)
	{
		$this->pathItems = $this->bucket->pathItems($label);
		return $this;
	}

	/**
	 * Delegates the rendering to BreadcrumbRendererInterface
	 * @return mixed
	 */
	public function render()
	{
		return $this->renderer->renderBreadcrumb($this->pathItems);
	}
}