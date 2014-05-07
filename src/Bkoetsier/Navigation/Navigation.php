<?php namespace Bkoetsier\Navigation;

use Bkoetsier\Navigation\Exceptions\CurrentItemIdMissingException;
use Bkoetsier\Navigation\Exceptions\ItemIdNotFoundException;
use Bkoetsier\Navigation\Renderer\ListRenderer;
use Illuminate\Support\Collection;

class Navigation {

	/**
	 * @var Bucket
	 */
	protected $bucket;
	/**
	 * @var
	 */
	protected $current;
	/**
	 * @var Renderer\ListRenderer
	 */
	protected $renderer;
	/**
	 * @var \Illuminate\Support\Collection
	 */
	protected $menus;

	/**
	 * @var \Bkoetsier\Navigation\Breadcrumbs
	 */
	protected $breadcrumbs;

	function __construct(Bucket $bucket)
	{
		$this->bucket = $bucket;
		$this->menus = new Collection;
	}

	public function setCurrent($id)
	{
		if (!$this->getBucket()->findById($id))
		{
			throw new ItemIdNotFoundException("Item with id: $id could not be found");
		}
		$this->current = $id;
		foreach($this->menus as $menu)
		{
			/**
			 * @var $menu \Bkoetsier\Navigation\Menu
			 */
			$menu->getRenderer()->setCurrent($id);
		}
		if (! is_null($this->breadcrumbs))
		{
			$this->breadcrumbs->getRenderer()->setCurrent($id);
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

	public function menu($name)
	{
		if(isset($this->menus[$name]))
		{
			return $this->menus[$name];
		}
		$menu = new Menu;
		$menu->setRenderer($this->getRenderer());
		$this->menus->put($name,$menu);
		return $this->menus[$name];
	}

	public function getMenus()
	{
		return $this->menus;
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


}