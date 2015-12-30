<?php
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
?>
<script type="text/javascript">
    function renderUnitNumbers(unit, moveAmount){

        var  move = unit.maxMove - unit.moveAmountUsed;
        if(moveAmount !== undefined){
            move = moveAmount-0;
        }
        move = move.toFixed(2);
        move = move.replace(/\.00$/,'');
        move = move.replace(/(\.[1-9])0$/,'$1');
        var str = unit.strength;
        var reduced = unit.isReduced;
        var reduceDisp = "<span class='unit-info'>";
        if(reduced){
            reduceDisp = "<span class='unit-info reduced'>";
        }
        if(unit.class === "wagon"){
            str = "("+str+")";
        }
        var symb = unit.supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+unit.defStrength+"-";
        var html = reduceDisp + str + symb + move + "</span>";
        return html;



    }

</script>
<style type="text/css">
<?php
include "all.css";?>
</style>