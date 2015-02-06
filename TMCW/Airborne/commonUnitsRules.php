<span class="big">UNITS</span>

<p>The units are in two colors.</p>
<ol>
    <li>
        <?= $playerOne ?> units are this color.
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiPara.png" class="counter">
            </div>

            <div class="unit-numbers">8 - 5</div>
        </div>
    </li>
    <li>
        <?= $playerTwo ?> units are this color.
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiGor.png" class="counter">
            </div>
            <div class="unit-numbers">3 - 4</div>
        </div>
        <div class="unit loyalGuards" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiArmor.png" class="counter">
            </div>
            <div class="unit-numbers">13 - 8</div>
        </div>    </li>
    <li>
        The symbol above the numbers represents the unit type.
        This is Armor (tanks).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">

            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiArmor.png" class="counter">
            </div>

            <div class="unit-numbers">6 - 8</div>
        </div>
    </li>
    <li>
        This is Mechinized Infantry (soldiers in half tracks, with small arms).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>
            <div class="unit-numbers">4 - 8</div>
        </div>
    </li>
    <li>
        This is Infantry. (soldiers on foot, with small arms).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiInf.png" class="counter">
            </div>

            <div class="unit-numbers">2 - 8</div>
        </div>
    </li>
    <li>
        This is Airborne troops. (soldiers that jump out of planes with parachutes. They are armed with small arms).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiPara.png" class="counter">
            </div>

            <div class="unit-numbers">9 - 5</div>
        </div>
    </li>
    <li>
        This is Glider troops. (soldiers that land in gliders, they have more heavy weapons than Airborne troops).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiGlider.png" class="counter">
            </div>

            <div class="unit-numbers">10 - 5</div>
        </div>
    </li>
    <li>
        This is Garrison troops. (older solders with older weapons).
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiGor.png" class="counter">
            </div>

            <div class="unit-numbers">3 - 4</div>
        </div>
    </li>
    <li>
        The number on the left is the combat strength. The number on the right is the movement allowance
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>

            <div class="unit-numbers">9 - 6</div>
        </div>
        <p class="ruleComment">
            The above unit has a combat strength of 9 and a movenent allowance of 6.</p>
    </li>
    <li>
        If a units numbers are in white, that means this unit is at reduced strength and can receive
        replacements
        during the replacement phase.
        <div class="clear"></div>
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiArmor.png" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">3 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">4 - 6</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
</ol>