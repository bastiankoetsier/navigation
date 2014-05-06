<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Exceptions\CurrentItemIdMissingException;
use Bkoetsier\Navigation\Exceptions\ItemIdNotFoundException;
use Bkoetsier\Navigation\Renderer\ListRenderer;

class Navigation {

	protected $bucket;
	protected $current;
	/**
	 * @var Renderer\ListRenderer
	 */
	protected $renderer;
	protected $menu;
	protected $breadcrumbs;

	function __construct(Bucket $bucket)
	{
		$this->bucket = $bucket;
	}

	public function setCurrent($id)
	{
		if (!$this->getBucket()->findById($id))
		{
			throw new ItemIdNotFoundException("Item with id: $id could not be found");
		}
		$this->current = $id;
		if (! is_null($this->renderer))
		{
			$this->renderer->setCurrent($id);
		}
		return $this;
	}

	public function getCurrent()
	{
		if(is_null($this->current))
		{
			$this->current = $this->getBucket()->getCollection()->first()->getId();
		}
		return $this->current;
	}

	public function fill()
	{
		call_user_func_array([$this->getBucket(),'hydrate'],func_get_args());
		return $this;
	}

	public function menu()
	{
		if(! is_null($this->menu))
		{
			return $this->menu;
		}
		$this->menu = new Menu;
		$this->menu->setRenderer($this->getRenderer());
		return $this->menu;
	}

	public function breadcrumbs()
	{
		if( ! is_null($this->breadcrumbs))
		{
			return $this->breadcrumbs;
		}
		$this->breadcrumbs = new Breadcrumbs;
		$this->breadcrumbs->setRenderer($this->getRenderer());
		return $this->breadcrumbs;
	}

	public function getBucket()
	{
		return $this->bucket;
	}

	protected function initializeRenderer()
	{
		$this->renderer = new ListRenderer($this->getBucket());
		$this->renderer->setCurrent($this->getCurrent());
	}

	public function getRenderer()
	{
		if( is_null($this->renderer))
		{
			$this->initializeRenderer();
		}
		return $this->renderer;
	}

	public function setMaxLevel($max)
	{
		$this->getRenderer()->setMaxLevel($max);
		return $this;
	}

}