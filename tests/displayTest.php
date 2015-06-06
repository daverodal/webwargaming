<?php
class DisplayTest extends PHPUnit_Framework_TestCase
{
	public function testThis(){
		$this->assertEquals(0,0);
        $k = new Klissow1702();
		$d = new Display();
        $v = new MapViewer();
		$this->assertEquals(true, is_object($d));
		$this->assertObjectHasAttribute('messages', $d);
	}
}
