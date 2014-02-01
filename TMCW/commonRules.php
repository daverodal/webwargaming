<style type="text/css">
    #header{
        /*display:none;*/
    }
    .exclusive {
        color: green;
    }
    #gameRules{
        font-family:sans-serif;
    }
    #gameRules table, #gameRules th, #gameRules td{
        border:1px solid black;
    }
    #gameRules h1{
        color:#338833;
        font-size:60px;

    }
    #GR #credits h2{
        color:#338833;
    }
    #GR li{
        margin: 3px 0;
    }
    #GR h4{
        margin-bottom:5px;
    }
    #GR #credits h4{
        margin-bottom:0px;
    }
    #gameRules h4:hover{
        text-decoration: none;
    }
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Rules</h4>
    <div id="GR" style="display:none">
        <div class="close">X</div>
<div id="gameRules">
    <?php $playerOne = $force_name[1];
    $playerTwo = $force_name[2];?>
    <h1>    <?= $name ?>    </h1>
    <h2>Rules of Play</h2>
    <h2>Design Context</h2>
    <p><?= $name?> is a continuation of the gaming framework first pioneered by the game The Martian Civil War. We hope you enjoy playing our game.</p>
    <H2>The Units</H2>
    <h4>Unit Colors</h4>
    <p>The units are in two colors.</p>
    <?=$playerOne?> units are this color. <div class="unit rebel" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img src="<?=base_url();?>js/multiArmor.png" class="counter">
        <div class="unit-numbers">6 - 8</div>
    </div>
    <p></p>
    <?=$playerTwo?> units are this color.
    <div class="unit <?=strtolower($playerTwo)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img class="arrow" src="<?=base_url();?>js/short-red-arrow-md.png" style="opacity: 0;">
        <img src="<?=base_url();?>js/multiMech.png" class="counter">

        <div class="unit-numbers">9 - 6</div>

    </div>

    <p> The symbol above the numbers represents the unit type.</p>
    This is Armor (tanks).
    <div class="unit <?=strtolower($playerOne)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img src="<?=base_url();?>js/multiArmor.png" class="counter">
        <div class="unit-numbers">6 - 8</div>
    </div>
    <p></p>This is Mechinized Infantry (soldiers in half tracks, with small arms).
    <div class="unit <?=strtolower($playerOne)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img src="<?=base_url();?>js/multiMech.png" class="counter">
        <div class="unit-numbers">4 - 8</div>
    </div>
    <p></p>This is Infantry. (soldiers on foot, with small arms).
    <div class="unit <?=strtolower($playerOne)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img src="<?=base_url();?>js/multiInf.png" class="counter">
        <div class="unit-numbers">2 - 8</div>
    </div>
    <p></p>
    The number on the left is the combat strength. The number on the right is the movement allowance
    <div class="unit <?=strtolower($playerTwo)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img src="<?=base_url();?>js/multiMech.png" class="counter">
        <div class="unit-numbers">9 - 6</div>
    </div>
    The above unit has a combat strength of 9 and a movenent allowance of 6.
    <p></p>
    If a units numbers are in white, that means this unit is at reduced strength and can receive replacements during the replacement phase.
    <div class="clear"></div>
    <div class="unit <?=strtolower($playerOne)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img src="<?=base_url();?>js/multiArmor.png" class="counter">
        <div class="unit-numbers"><span class="reduced">3 - 8</span></div>
    </div>
    <div class="unit <?=strtolower($playerTwo)?>" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;"><section></section>
        <div class="unitSize">xx</div>
        <img class="arrow" src="<?=base_url();?>js/short-red-arrow-md.png" style="opacity: 0;">
        <img src="<?=base_url();?>js/multiMech.png" class="counter">

        <div class="unit-numbers"><span class="reduced">4 - 6</span></div>

    </div>

    <div class="clear">&nbsp;</div>
    <h2>Game Play</h2>
    <p>The game is made up of 7 Game turns, each Game turn consists of two player turns, Each player turn has several phases. These are described below in the sequence of play.</p>
    <h4>Sequence of Play.</h4>
    <ul>
        <li>
            <h4><?=$playerOne?> Player Turn</h4>
            <ol>
                <li>
                    <h4>Replacement Phase</h4>
                    The phasing player may allocate as many replacements as they received. <?=$playerOne?> forces receive one replacement per turn. (There is no replacement phase
                    for the <?=$playerOne?> player on turn one).
                </li>
                <li>
                    <h4>Movement Phase</h4>
                    The phasing player may move any or all of their units. Movement is voluntary.
                </li>
                <li>
                    <h4>Combat Phase</h4>
                    The phasing player may any and all units that adjacent to their units. Combat is voluntary.
                </li>
                <li>
                    <h4>Second Movement Phase</h4>
                    The phasing player may move any or all of their <strong>Armored</strong> or <strong>mechinized infantry</strong> units. Infantry units may <strong>not</strong> move in the second movement phase.
                </li>
            </ol>
        </li>
        <li>
            <h4><?=$playerTwo?> Player Turn</h4>
            <ol>
                <li>
                    <h4>Replacement Phase</h4>
                    The phasing player may receive as many replacements as they are allocated. <?=$playerTwo?>s receive 10 replacements per turn.
                </li>
                <li>
                    <h4>Movement Phase</h4>
                    The phasing player may move any or all of their units. Movement is voluntary.
                </li>
                <li>
                    <h4>Combat Phase</h4>
                    The phasing player may any and all units that adjacent to their units. Combat is voluntary.
                </li>
                <li>
                    <h4>Second Movement Phase</h4>
                    The phasing player may move any or all of their <strong>Armored</strong> or <strong>mechinized infantry</strong> units. Infantry units may <strong>not</strong> move in the second movement phase.
                </li>
            </ol>
        </li>
    </ul>
    <p>At the end of 7 game turns the game is over and victory is determined.</p>
    <h2>Movement</h2>

    <p>The Second Number on the counter is Movement Points <abbr title="Movement Points">(MP)</abbr>.</p>

    <h4>Units expend different amounts of <abbr title="Movement Points">MP</abbr> for different terrains. Also, different units may expend
    different amounts to enter certain hexes.</h4>
    <ul>
        <li>The number of movement points or <abbr title="Movement Points">MP</abbr>'s a unit expends to enter a hex is baseed
            up both the unit type and the hex being entered. It can also be affected by the hex side be traversed (example, a river hexside).
            Please see the Terrain Effects Chart or
            <abbr title="Terrain Effects Chart">TEC</abbr> for the effects of terrain on movement. The <abbr title="Terrain Effects Chart">TEC</abbr> may be found by pressing
            the button <abbr title="Terrain Effects Chart">TEC</abbr>.
        </li>
        </ul>
    <h3>Road Movement</h3>
    <ul>

        <li>    <p>When a unit moves from a hex containing a road, to another hex containing a road, and a road traverses the hexside
                be traversed, the unit may be eligible for road movement. Road movement often requires less
                <abbr title="Movement Points">MP</abbr>'s than the other terrain in the hex.</p>

        </li>
        </ul>
    <h3>Zones of Control</h3>
    <ul>
        <li><p>
                The six hexes surrounding a unit constitute it's Zone of Control or <abbr title="Zone Of Control">ZOC</abbr>.
                <abbr title="Zone Of Control">ZOC</abbr>'s affect the movement of enemy units. The affect is dependant upon
                many factors.
        </p><p>When a unit enters a hostile <abbr title="Zone Of Control">ZOC</abbr> it must either stop and move no further, OR, expend a
            certain amounts of <abbr title="Zone Of Control">MP's</abbr>to enter the hex, depending upon the unit. If a units starts the
            turn in a <abbr title="Zone Of Control">ZOC</abbr>, it may require movement points to leave the hex, depending upon the unit type.</p>
            <ul>
                <li>Mechanized units<p>A mechanized unit (units with a second movement phase) require 2 additional movement points to enter a zoc. They also
                require 1 additional MP to leave a zoc.</p>
                <p>Mechanized units may move directly from one <abbr title="Zone Of Control">ZOC</abbr> to another
                at the price of 3 additional <abbr title="Zone Of Control">MP's</abbr>'s</p></li>
                <li>Infantry units
                    <p>Infantry units must stop upon entering a <abbr title="Zone Of Control">ZOC</abbr>. Infantry units that start
                    their movement phase in a <abbr title="Zone Of Control">ZOC</abbr> may exit without penalty, and re-enter a <abbr title="Zone Of Control">ZOC</abbr>
                    provided they do not move directly from one <abbr title="Zone Of Control">ZOC</abbr> to another.</p>

                    <?php if ($name == "Manchuria1976") { ?>
                    <p class="exclusive"><?= $playerTwo ?> Infantry that start their turn in a mountain hex in an enemy <abbr title="Zone Of Control">ZOC</abbr>, may move directly to another
                    hexagon with an enemy <abbr title="Zone Of Control">ZOC</abbr>,at which point they must stop and move no further.</p>
                <?php } ?>
                </li>
            </ul>
        </li>
        <li>Regardless of movement points required, a unit may always move at least one hex per turn,
            provided they are not moving directly from one zoc to another.
        </li>
    </ul>
</div>
        </div></div>