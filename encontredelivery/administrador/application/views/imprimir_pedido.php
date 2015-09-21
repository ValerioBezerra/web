<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"> 
<head>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico')?>">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
</head>
<body> 

<!-- 	<table> -->
		{CABECALHO}
			<h7>{LINHA}</h7>
		{/CABECALHO}
<!-- 	</table> -->

<script> 
function printpr() 
{ 
//var OLECMDID = 10; 
/* OLECMDID values: 
* 6 - print 
* 7 - print preview 
* 8 - page setup (for printing) 
* 1 - open window 
* 4 - Save As 
* 10 - properties 
*/ 
var PROMPT = 1; // 1 PROMPT & 2 DONT PROMPT USER 
var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); 
WebBrowser1.ExecWB(7,PROMPT); 
WebBrowser1.outerHTML = "";


} 

printpr();
</script>

<SCRIPT Language="Javascript"> 
function printit(){ 
var NS = (navigator.appName == "Netscape"); 
var VERSION = parseInt(navigator.appVersion);

if (NS) { 
window.print() ; 
} else { 
	var PROMPT = 1; // 1 PROMPT & 2 DONT PROMPT USER 
	var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
	document.body.insertAdjacentHTML('beforeEnd', WebBrowser); 
	WebBrowser1.ExecWB(7,PROMPT); 
	WebBrowser1.outerHTML = "";} 
} 
</script> 

<SCRIPT Language="Javascript"> 
printit();
</script> 

</body> 