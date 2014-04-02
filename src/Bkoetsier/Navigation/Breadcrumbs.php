<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Renderer\BreadcrumbRendererInterface;

class Breadcrumbs {

	protected $bucket;
	protected $pathItems;
	/**
	 * @var Renderer\BreadcrumbRendererInterface
	 */
	private $renderer;

	function __construct(Bucket $bucket,BreadcrumbRendererInterface $renderer)
	{
		$this->bucket = $bucket;
		$this->renderer = $renderer;
	}

	public function pathTo($label)
	{
		$this->pathItems = $this->bucket->pathItems($label);
		return $this;
	}

	public function render()
	{
		return $this->renderer->renderBreadcrumb($this->pathItems);
	}
} 