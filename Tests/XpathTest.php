<?php
namespace H0gar\Xpath\Tests;

class XpathTest extends \PHPUnit_Framework_TestCase {
	public function test() {
		#init
		$html = file_get_contents(__DIR__.'/page.html');
		$doc = new \H0gar\Xpath\Doc($html);

		#get HTML code
		$this->assertInstanceOf('DOMXpath', $doc->getXpath());
		$this->assertEquals($html, $doc->getCode());
		$this->assertEquals('fcdc49e8fd7c2bd00d7a1af0017613cd234e839e', sha1($this->normalize($doc->html())));

		#get element's inner html
		$this->assertEquals('<a href="#">Home</a>', $doc->html('/html/body/div[1]/div/div[2]/ul/li'));
		$this->assertEquals('<a href="#about">About</a>', $doc->html('/html/body/div[1]/div/div[2]/ul/li', 1));

		#get element's text
		$this->assertEquals('Bootstrap starter template', $doc->text('/html/body/div[2]/div/h1'));
		$this->assertEquals('About', $doc->text('/html/body/div[1]/div/div[2]/ul/li', 1));

		#get items
		$this->assertInstanceOf('H0gar\Xpath\Node', $doc->item('/html/body/div[1]/div/div[2]/ul/li'));
		$this->assertInstanceOf('DOMElement', $doc->item('/html/body/div[1]/div/div[2]/ul/li')->getNode());
		$this->assertInstanceOf('H0gar\Xpath\Node', $doc->item('/html/body/div[1]/div/div[2]/ul/li', 1));
		$this->assertInstanceOf('DOMElement', $doc->item('/html/body/div[1]/div/div[2]/ul/li', 1)->getNode());
		$this->assertInstanceOf('H0gar\Xpath\Node', $doc->item('/html/body/div[1]/div/div[2]/ul/li', 5));
		$this->assertNull($doc->item('/html/body/div[1]/div/div[2]/ul/li', 5)->getNode());
		$this->assertInstanceOf('DOMXPath', $doc->item('/html/body/div[1]/div/div[2]/ul/li')->getXpath());

		#get item's html
		$this->assertEquals($doc->html('/html/body/div[1]/div/div[2]/ul/li'), $doc->item('/html/body/div[1]/div')->html('div[2]/ul/li'));
		$this->assertEquals($doc->html('/html/body/div[1]/div/div[2]/ul/li', 1), $doc->item('/html/body/div[1]/div')->html('div[2]/ul/li', 1));

		#get item's text
		$this->assertEquals($doc->text('/html/body/div[2]/div/h1'), $doc->item('/html/body/div[2]')->text('div/h1'));
		$this->assertEquals($doc->text('/html/body/div[1]/div/div[2]/ul/li', 1), $doc->item('/html/body/div[1]/div')->text('div[2]/ul/li', 1));

		#get attributes
		$this->assertEquals('#about', $doc->item('/html/body/div[1]/div/div[2]/ul/li[2]/a')->attr('href'));

		#multiple items
		$items = $doc->items('/html/body/div[1]/div/div[2]/ul/li');
		$this->assertCount(3, $items);
		$this->assertEquals('Home', $items[0]->text());

		#navigation
		$item = $doc->item('/html/body/div[1]/div/div[2]/ul/li[2]');
		$this->assertEquals('Home', $item->prev()->text());
		$this->assertEquals('Contact', $item->next()->text());
		$this->assertEquals('Home
            About
            Contact', $item->parent()->text());
	}

	protected function normalize($s) {
	    $s = str_replace("\r\n", "\n", $s);
	    $s = str_replace("\r", "\n", $s);
	    $s = preg_replace("/\n{2,}/", "\n\n", $s);
	    return $s;
	}
}