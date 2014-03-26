<?php namespace Bkoetsier\Navigation\Renderer;

use Bkoetsier\Navigation\Items\ItemInterface;
use Bkoetsier\Navigation\Items\LinkItem;

class ListRenderer implements RenderableInterface{

	protected $items = [];
	protected $tag = 'ul';

	public function render($items)
	{
		$this->setItems($items);
		$output = '';
		$itemOutput = '';
		$output = '<%s>%s</%s>';

		foreach($items as $item)
		{
			$itemOutput .= '<li>';
			/**
			 * @var $item \Bkoetsier\Navigation\Items\ItemInterface
			 */
			if(is_a($item, '\Bkoetsier\Navigation\Items\LinkItem'))
			{
				$itemOutput .= $this->renderLink($item);
			}
			else
			{
				$itemOutput .= $this->renderItem($item);
			}

			$itemOutput .= '</li>';
		}
		return sprintf($output,$this->getElement(),$itemOutput,$this->getElement());
	}

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
		return sprintf('<a href="%s">%s</a>',$item->getUri(),$item->getLabel());
	}


}