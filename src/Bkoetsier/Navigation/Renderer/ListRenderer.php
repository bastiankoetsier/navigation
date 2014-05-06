<?php namespace Bkoetsier\Navigation\Renderer;

use Bkoetsier\Navigation\Bucket;
use Bkoetsier\Navigation\Items\Item;
use Illuminate\Support\Collection;

class ListRenderer implements BreadcrumbsRendererInterface,MenuRendererInterface{

	/**
	 * @var \Bkoetsier\Navigation\Bucket
	 */
	protected $bucket;
	protected $done = array();
	protected $maxLevel = 999;
	private $current;

	function __construct(Bucket $bucket)
	{
		$this->bucket = $bucket;
	}

	public function setCurrent($current)
	{
		$this->current = $current;
	}

	public function getCurrent()
	{
		return $this->current;
	}

	public function getBucket()
	{
		return $this->bucket;
	}

	public function renderMenu()
	{
		$this->refresh();
		$html = '<ul>';
		$currentItem = $this->getCurrentItem();
		/**
		 * search for latest parent in this tree
		 * otherwise list would only contain $currentItem
		 */
		while( ! $this->getBucket()->hasChildren($currentItem))
		{
			$currentItem = $this->getBucket()->findById($currentItem->getParent());
		}
		$children = $this->getBucket()->getChildrenAndSelf($currentItem);
		$filteredItems = $this->bucket->getUntilMaxLevel($this->getMaxLevel(),$children);
		foreach($filteredItems as $item)
		{
			$item = $this->markIfActive($item);
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			if(in_array($item->getId(),$this->done))
			{
				continue;
			}
			$this->done[] = $item->getId();
			$html .= '<li>'.$item->getContent();
			if(  ! $this->bucket->hasChildren($item,$filteredItems))
			{
				$html .= '</li>';
			}
			else
			{
				$html .= $this->subMenu($item,$filteredItems);
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}

	protected function subMenu(Item $parent,Collection $filtered)
	{
		$html = '';
		$html .= '<ul>';
		$children = $this->bucket->getChildrenWithoutSelf($parent,$filtered);
		if( ! count($children) )
		{
			return '';
		}
		foreach($children as $c)
		{
			$c = $this->markIfActive($c);
			/**
			 * @var $c \Bkoetsier\Navigation\Items\Item
			 */
			if($this->isAlreadyUsed($c->getId()))
			{
				continue;
			}
			if($parent->getLevel() +1 <= $this->getMaxLevel())
			{
				$html .= '<li>'.$c->getContent();
				$html .= $this->subMenu($c,$filtered);
				$html .= '</li>';
				$this->done[] = $c->getId();
			}
			else
			{
				$this->done[] = $c->getId();
				return '';
			}
		}
		$html .= '</ul>';
		return $html;
	}

	public function renderBreadcrumbs()
	{
		$items = $this->getBucket()->pathItems($this->getCurrent());
		$html = '<ul>';
		foreach($items as $item)
		{
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			$html .= sprintf('<li>%s</li>',$item->getContent());
		}
		return $html;
	}

	protected function isAlreadyUsed($id)
	{
		if(in_array($id,$this->done))
		{
			return true;
		}
		return false;
	}

	protected function getCurrentItem()
	{
		return $this->getBucket()->findById($this->getCurrent());
	}

	protected function refresh()
	{
		$this->done = array();
	}

	/**
	 * @return int
	 */
	public function getMaxLevel()
	{
		return $this->maxLevel;
	}

	/**
	 * @param int $maxLevel
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setMaxLevel($maxLevel)
	{
		if($maxLevel < 0)
		{
			throw new \InvalidArgumentException("Max Level must be higher greater or equal to 0");
		}
		$this->maxLevel = $maxLevel;
		return $this;
	}

	/**
	 * @param \Bkoetsier\Navigation\Items\Item $item
	 * @return string
	 */
	protected function markIfActive(Item $item)
	{
		if ($this->isActiveItem($item))
		{
			$content = sprintf('<span class="active">%s</span>',$item->getContent());
			$item->setContent($content);
		}
		return $item;
	}

	protected function isActiveItem(Item $item)
	{
		return $this->getCurrentItem()->getId() == $item->getId();
	}
}