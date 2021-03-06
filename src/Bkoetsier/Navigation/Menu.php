<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Renderer\MenuRendererInterface;

class Menu {

	protected $renderer;

	public function setRenderer(MenuRendererInterface $renderer)
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

	public function render($withSelf = true)
	{
		return $this->getRenderer()->renderMenu($withSelf);
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