<?php namespace Bkoetsier\Navigation\Renderer;

use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Items\LinkItem;

class ListRenderer implements MenuRendererInterface,BreadcrumbRendererInterface{

	protected $items = [];
	protected $tag = 'ul';

	/**
	 * @param array $items
	 */
	public function setItems($items)
	{
		$this->items = $items;
	}

	public function setElement($element)
	{
		$this->tag = $element;
	}

	public function getElement()
	{
		return $this->tag;
	}

	protected function renderItem(ItemInterface $item)
	{
		return $item->getLabel();
	}

	protected function renderLink(LinkItem $item)
	{
		return sprintf('<a href="%s">%s</a>',url($item->getUri()),$item->getLabel());
	}

	public function renderMenu(ItemInterface $parentItem, $maxDepth = 3,$ul = true)
	{
		$output = '<%s>%s</%s>';
		$itemOutput = '';
		$itemOutput .= '<li>';
		$itemOutput .= $this->renderLink($parentItem);
		$itemOutput .= '</li>';
		if ($parentItem->hasChildren() && ($parentItem->getLevel() + 1) <= $maxDepth)
		{
			foreach($parentItem->getChildren() as $child)
			{
				$itemOutput .= $this->renderMenu($child,$maxDepth);
			}
		}
		return sprintf($output,$this->getElement(),$itemOutput,$this->getElement());
	}

	public function renderBreadcrumb($items)
	{
		$output = '<%s>%s</%s>';
		$itemOutput = '';
		foreach($items as $item)
		{
			/**
			 * @var $item ItemInterface
			 */
			$itemOutput .= '<li>'.$this->renderLink($item).'</li>';
		}
		return sprintf($output,$this->getElement(),$itemOutput,$this->getElement());
	}
}