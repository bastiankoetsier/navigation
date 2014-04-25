<?php namespace Bkoetsier\Navigation;


use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Renderer\MenuRendererInterface;

class Menu {

	/**
	 * @var Bucket
	 */
	protected $bucket;

	/**
	 * @var ItemInterface
	 */
	protected $parentItem;

	/**
	 * @var Renderer\MenuRendererInterface
	 */
	protected $renderer;

	function __construct(Bucket $bucket,MenuRendererInterface $renderer)
	{
		$this->bucket = $bucket;
		$this->renderer = $renderer;
	}

	/**
	 * @return Bucket
	 */
	public function getBucket()
	{
		return $this->bucket;
	}

	/**
	 * Delegates the data to the bucket´s 'hydrate'-method
	 */
	public function fill()
	{
		call_user_func_array([$this->getBucket(),'hydrate'],func_get_args());
		return $this;
	}

	/**
	 * Adds new item to bucket-root
	 * @param ItemInterface $item
	 */
	public function add(ItemInterface $item)
	{
		$this->bucket->add($item);
	}

	/**
	 * Searches the bucket for $parentLabel
	 * and sets it as the $parentItem for rendering
	 * @param $parentLabel
	 * @return $this|bool
	 */
	public function subNav($parentLabel)
	{
		$parent = $this->bucket->find($parentLabel);
		if($parent)
		{
			$this->parentItem = $parent;
			return $this;
		}
		else
			return false;
	}

	/**
	 * Delegates the rendering to the BreadcrumbRendererInterface
	 * with $parentItem
	 * @param int $maxDepth
	 * @return mixed
	 */
	public function render($maxDepth = 3)
	{
		return $this->renderer->renderMenu($this->parentItem,$maxDepth);
	}

	public function setRenderer(MenuRendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	public function getParentItem()
	{
		return $this->parentItem;
	}

}