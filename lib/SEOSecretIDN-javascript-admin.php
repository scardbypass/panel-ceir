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