<?php namespace Bkoetsier\Navigation\Test\Integration\Renderer;

use Bkoetsier\Navigation\Bucket;
use Bkoetsier\Navigation\Items\Item;
use Bkoetsier\Navigation\Renderer\MenuRenderer;
use Illuminate\Support\Collection;

class RendererTest extends \PHPUnit_Framework_TestCase {

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
	public function it_returns_complete_correctly_rendered_html_ul_list()
	{
	    $bucket = $this->getFixtures();
		$renderer = new MenuRenderer($bucket);
		$expectedHtml = '<ul><li>Root item</li><li>root 2<ul><li>child<ul><li>childchild</li></ul></li></ul></li></ul>';
		$this->assertEquals($expectedHtml,$renderer->render());
	}
	
	/**
	 * @test
	 */
	public function it_returns_correctly_rendered_html_ul_list_limited_to_level_one()
	{
		$bucket = $this->getFixtures();
		$renderer = new MenuRenderer($bucket);
		$expectedHtml = '<ul><li>Root item</li><li>root 2<ul><li>child</li></ul></li></ul>';
		$this->assertEquals($expectedHtml,$renderer->setMaxLevel(1)->render());
	}
}
 