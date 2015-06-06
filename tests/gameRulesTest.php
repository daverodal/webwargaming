<?php
class GameRulesTest extends PHPUnit_Framework_TestCase
{
    public $mapViewer;
	public function testThis(){

		$this->assertEquals(0,0);
        $klissow = new Klissow1702();
        $mapViewer =  new MapViewer();
        Battle::$class = $klissow;
        $klissow->gameRules->phase = BLUE_MOVE_PHASE;
        var_dump($klissow->victory->postRecoverUnits());
        var_dump($klissow->gameRules->flashMessages);
        $this->assertEquals(is_object($mapViewer), true);
        $this->assertEquals(is_object($klissow), true);
        $mapViewer->setData(1,2,3,4,5,6);
        $this->assertEquals($mapViewer->originX, 1);
        $this->assertEquals($mapViewer->originY, 2);
        $this->assertEquals($mapViewer->topHeight, 3);
        $this->assertEquals($mapViewer->bottomHeight, 4);
        $this->assertEquals($mapViewer->hexsideWidth, 5);
        $this->assertEquals($mapViewer->centerWidth, 6);
    }
}
