<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<style type="text/css">
<?php @include "all.css";?>
</style>
<script type="text/javascript">
x.register("vp", function(vp){
        $("#victory").html(" Victory: <span class='playerOneFace'><?=$force_name[1]?> </span>"+vp[1]+ " <span class='playerTwoFace'><?=$force_name[2];?> </span>"+vp[2]+" Surrounded Soviets "+vp[3]);
});
</script>