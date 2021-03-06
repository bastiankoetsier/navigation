<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Renderer\BreadcrumbsRendererInterface;

class Breadcrumbs {

	protected $renderer;

	public function setRenderer(BreadcrumbsRendererInterface $renderer)
	{
		$this->renderer = $renderer;
		return $this;
	}
	/**
	 * @return \Bkoetsier\Navigation\Renderer\ListRenderer
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}

	public function render()
	{
		return $this->getRenderer()->renderBreadcrumbs();
	}

	public function setCurrent($id)
	{
		$this->getRenderer()->setCurrent($id);
		return $this;
	}

	public function setMaxLevel($max)
	{
		$this->getRenderer()->setMaxLevel($max);
		return $this;
	}

} 