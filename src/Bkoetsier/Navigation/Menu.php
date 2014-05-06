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

	public function render()
	{
		return $this->getRenderer()->renderMenu();
	}




} 