<style type="text/css">

    #TECWrapper #TECImage{
        float: left;
    }
    </style>
<div class="dropDown" id="TECWrapper">
    <h4 class="WrapperLabel" title='Terrain Effects Chart'>TEC</h4>
    <DIV id="TEC" style="display:none;"><div class="close">X</div>
        <img id="TECImage" src="<?=base_url()?>js/AmphTEC.png">
        <div class="left">
            <ul>
            <li>
                <div class="column-one">Clear</div>
                <div class="column-two">1 Movement Point</div>
                <div class="column-three">No Effect</div>
            </li>
            <li>
                <div class="column-one">
                    Beach
                </div>
                <div class="column-two">1 Movement Point</div>
                <div class="column-three">No Effect</div>
            </li>
            <li>
                <div class="column-one">
                    Forest
                </div>
                <div class="column-two">2 Movement Points</div>
                <div class="column-three">Shift one</div>
            </li>
            <li>
                <div class="column-one">
                    Swamp
                </div>
                <div class="column-two">3 Movement Points</div>
                <div class="column-three">Shift one</div>
            </li>
            <li>
                <div class="column-one">
                    Mountain
                </div>
                <div class="column-two">3 Movement (Mountain 2)</div>
                <div class="column-three">Shift two (shift one if mountain attacking)</div>
            </li>
            <li>
                <div class="column-one">
                    Road hexside
                </div>
                <div class="column-two">1/2 if crossing road hexside</div>
                <div class="column-three">No Effect</div>
            </li>
                <li>
                    <div class="column-one">&nbsp;</div>
                </li>
            <li>
                <div class="column-one">
                    <span>Town</span>
                </div>
                <div class="column-two">No Effect</div>
                <div class="column-three">Shift one</div>
            </li>
                <li>
                    <div class="column-one">
                        <span>River</span>
                    </div>
                    <div class="column-two">2 Movement Points</div>
                    <div class="column-three">Shift one, if all attacking across river.</div>
                </li>
            <!--    Empty one for the bottom border -->
            <li class="closer"></li>
        </ul>
            </div>
    </div>
</div>