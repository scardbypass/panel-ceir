<!--Scroll Indicator Load-->
<div class='progress-container'>
	<div class='progress-bar' id='progressbar'/>
</div>
<script type='text/javascript'>
	//<![CDATA[
	window.addEventListener("scroll", myFunction);
	function myFunction() {
		var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
		var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
		var scrolled = (winScroll / height) * 100;
		document.getElementById("progressbar").style.width = scrolled + "%";
	}
	//]]>
</script>

<!--Disable popup form resubmit after page refresh 2-->
<script type='text/javascript'>
	if ( window.history.replaceState ) {
	  window.history.replaceState(null, null, window.location.href);
	}
</script>

<!-- BacktoTop -->
<script type='text/javascript'>
//<![CDATA[
$(".back-top").each(function(){var $this=$(this);$(window).on("scroll",function(){$(this).scrollTop()>=100?$this.fadeIn(250):$this.fadeOut(250)}),$this.click(function(){$("html, body").animate({scrollTop:0},500)})});
//]]>
</script>
<div class="back-top" title="Back to Top"></div>