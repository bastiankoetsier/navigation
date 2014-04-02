<?php namespace Bkoetsier\Navigation;


use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Renderer\MenuRendererInterface;

class Menu {


	protected $bucket;
	protected $parentItem;

	/**
	 * @var Renderer\RenderableInterface
	 */
	private $renderer;

	function __construct(Bucket $bucket,MenuRendererInterface $renderer)
	{
		$this->bucket = $bucket;
		$this->renderer = $renderer;
	}

	public function getBucket()
	{
		return $this->bucket;
	}

	public function fill()
	{
		call_user_func_array([$this->getBucket(),'hydrate'],func_get_args());
	}

	public function add(ItemInterface $item)
	{
		$this->bucket->add($item);
	}

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

	public function render($maxDepth = 3)
	{
		return $this->renderer->renderMenu($this->parentItem,$maxDepth);
	}

}