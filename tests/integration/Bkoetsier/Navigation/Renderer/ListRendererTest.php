<?php namespace Bkoetsier\Navigation\Test\Integration\Renderer;

use Bkoetsier\Navigation\Bucket;
use Bkoetsier\Navigation\Items\Item;
use Bkoetsier\Navigation\Renderer\ListRenderer;
use Illuminate\Support\Collection;

class ListRendererTest extends \PHPUnit_Framework_TestCase {

	protected function getFixtures()
	{
		$bucket = new Bucket(new Collection());
		$root = new Item('Root item',1);
		$root2 = new Item('root 2',2);
		$child = new Item('child',3);
		$bucket->addRoot($root);
		$bucket->addRoot($root2);
		$bucket->addChild($child,$root2);
		$childchild = new Item('childchild',4);
		$bucket->addChild($childchild, $child);
		return $bucket;
	}

	/**
	 * @test
	 */
	public function it_returns_partial_part_and_correctly_rendered_html_ul_list()
	{
	    $bucket = $this->getFixtures();
		$renderer = new ListRenderer($bucket);
		$renderer->setCurrent(3);
		$expectedHtml = '<ul><li><span class="active">child</span><ul><li>childchild</li></ul></li></ul>';
		$this->assertEquals($expectedHtml,$renderer->renderMenu());
	}
	
	/**
	 * @test
	 */
	public function it_returns_correctly_rendered_html_ul_list_limited_to_level_one()
	{
		$bucket = $this->getFixtures();
		$renderer = new ListRenderer($bucket);
		$renderer->setCurrent(2);
		$expectedHtml = '<ul><li><span class="active">root 2</span><ul><li>child</li></ul></li></ul>';
		$this->assertEquals($expectedHtml,$renderer->setMaxLevel(1)->renderMenu());
	}
}
 