<?php namespace Bkoetsier\Navigation\Renderer;

use Bkoetsier\Navigation\Items\ItemInterface;

interface MenuRendererInterface {
	
	public function renderMenu(ItemInterface $parentItem,$maxDepth = 3,$ul = true);

} 