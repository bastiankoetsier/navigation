<?php namespace Bkoetsier\Navigation\Renderer;

use Bkoetsier\Navigation\Bucket;
use Bkoetsier\Navigation\Items\Item;

class Renderer {

	/**
	 * @var \Bkoetsier\Navigation\Bucket
	 */
	protected $bucket;
	protected $done = array();
	protected $maxLevel = 999;

	function __construct(Bucket $bucket)
	{
		$this->bucket = $bucket;
		$this->refresh();
	}

	public function render()
	{
		$this->refresh();
		$html = '<ul>';
		foreach($this->bucket->getCollection() as $item)
		{
			/**
			 * @var $item \Bkoetsier\Navigation\Items\Item
			 */
			if(in_array($item->getId(),$this->done))
			{
				continue;
			}
			$this->done[] = $item->getId();
			$html .= '<li>'.$item->getLabel();
			if(  ! $this->bucket->hasChildren($item))
			{
				$html .= '</li>';
			}
			else
			{
				$html .= $this->subMenu($item);
				$html .= '</li>';
			}
		}
		$html .= '</ul>';
		return $html;
	}

	protected function subMenu(Item $parent)
	{
		$html = '';
		$html .= '<ul>';
		$children = $this->bucket->getChildrenWithoutSelf($parent);
		if( ! count($children) )
		{
			return '';
		}
		foreach($children as $c)
		{
			/**
			 * @var $c \Bkoetsier\Navigation\Items\Item
			 */
			if(in_array($c->getId(),$this->done))
			{
				continue;
			}
			if($parent->getLevel() +1 <= $this->getMaxLevel())
			{
				$html .= '<li>'.$c->getLabel();
				$html .= $this->subMenu($c);
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
			throw new \InvalidArgumentException("Max Level must be higher greater than 0");
		}
		$this->maxLevel = $maxLevel;
		return $this;
	}
}