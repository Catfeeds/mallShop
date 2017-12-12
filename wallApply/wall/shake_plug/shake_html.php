<!--摇一摇层-->
<script type="text/javascript">
$(function(){
	var style = "<?php echo $style;?>";
	var companyid = "<?php echo $_SESSION['cid'];?>";
    $(document).keydown(function (event)
        {   
        if(event.keyCode == 89){
        		window.open(webroot+'/shake/index.php?style='+style+'&companyid='+companyid);
        		}
        });  
});
</script>
