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
<style type="text/css">
<?php
include "all.css";?>
</style>
<script type="text/javascript">
    x.register("specialHexes", function(specialHexes, data) {
        var lab = ['pontoon','<?=strtolower($force_name[1])?>','<?=strtolower($force_name[2])?>'];
        for(var i in specialHexes){
            var newHtml = lab[specialHexes[i]];
            var curHtml = $("#special"+i).html();

            if(true || newHtml != curHtml){
                var hexPos = i.replace(/\.\d*/g,'');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                $("#special"+hexPos).remove();
                if(data.specialHexesChanges[i]){
                    $("#gameImages").append('<div id="special'+hexPos+'" style="border-radius:30px;border:10px solid black;top:'+y+'px;left:'+x+'px;font-size:205px;z-index:1000;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');
                    $('#special'+hexPos).animate({fontSize:"16px",zIndex:0,borderWidth:"0px",borderRadius:"0px"},1900,function(){
                        var id = $(this).attr('id');
                        id = id.replace(/special/,'');


                        if(data.specialHexesVictory[id]){
                            var hexPos = id.replace(/\.\d*/g,'');

                            var x = hexPos.match(/x(\d*)y/)[1];
                            var y = hexPos.match(/y(\d*)\D*/)[1];
                            var newVp = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').insertAfter('#special'+i);
                            $(newVp).animate({top:y-30,opacity:0.0},1900,function(){
                                $(this).remove();
                            });
                        }
                    });

                }else{
                    if(specialHexes[i] === -1){
                        $("#gameImages").append('<div id="special'+i+'" class="specialHexes pontoon"></div>');
                    }else{
                        $("#gameImages").append('<div id="special'+i+'" class="specialHexes">'+lab[specialHexes[i]]+'</div>');
                    }
                    $("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);

                }

            }
        }
        for(var i in data.specialHexesVictory)
        {
            if(data.specialHexesChanges[i]){
                continue;
            }
            var id = i;
            var hexPos = id.replace(/\.\d*/g,'');
            var x = hexPos.match(/x(\d*)y/)[1];
            var y = hexPos.match(/y(\d*)\D*/)[1];
            var newVp = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
            $(newVp).animate({top:y-30,opacity:0.0},1900,function(){
                $(this).remove();
            });
        }


    });

</script>