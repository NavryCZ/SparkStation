<!DOCTYPE html>
<html lang="cs">
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/png" href="img/favicon.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<link rel="stylesheet" href="css/loader.css">
		<link rel="stylesheet" href="css/metro.css">
		<link rel="stylesheet" href="css/metro-all.css">
		<link rel="stylesheet" href="css/metro-colors.css"> 
		<link rel="stylesheet" href="css/metro-icons.css">
		<link rel="stylesheet" href="css/metro-rtl.css">
		<link rel="stylesheet" href="css/third-party/datatables.css">
		<link rel="stylesheet" href="css/third-party/select2.css">
		<link rel="stylesheet" href="css/schemes/sky-net.css">
        
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        
	</head>
	<body  style="background-color: #eaf1ff;">
		<div class="loader-body" id="loader">
			<div class="loader"></div>
		</div>
		<div data-role="appbar">
			<span class="mif-thermometer2 mif-5x fg-white"></span>
			<a href="http://spark.vpsd.eu/" class="brand no-hover">SPARK-STATION</a>
		</div>

		<div class="container" style="margin-top: 125px;">
			<div class="grid">
				<div class="row">
					<div class="cell-md-3">
					<center><p><strong>Teplota:</strong></p></center>
					<div id="donut_val" data-fill="#0CA9F2" data-animate="10" data-show-value="true" data-total="50" data-cap="°C" data-role="donut" data-value="17" class="mx-auto" data-size="120" data-radius="60" data-hole="0.9"></div>
					<hr class="thin">
					</div>
					
					<div class="cell-md-3">
					<center><p><strong>Vlhkost</strong></p></center>
					<div id="donut_va2" data-fill="#0CA9F2" data-animate="10" data-cap="%" data-role="donut" data-value="69" class="mx-auto" data-size="120" data-radius="60" data-hole="0.9"></div>
					<hr class="thin">
					
					</div>
					
					<div class="cell-md-3">
					<center><p><strong>Tlak</strong></p></center>
					<div id="donut_va3" class="mx-auto" data-fill="#0CA9F2" data-animate="1" data-cap="hPa" data-show-value="true" data-role="donut" data-total="2048" data-value="1005" data-fontSize="5" data-size="120" data-radius="60" data-hole="0.9"></div> 
					<hr class="thin"> 
					
					</div>

					<div class="cell-md-3">
					<center><p><strong>Baterie</strong></p></center>
					<div id="donut_va4" class="mx-auto" data-fill="#0CA9F2" data-animate="1" data-cap="%" data-show-value="false" data-role="donut" data-total="100" data-value="50" data-fontSize="5" data-size="120" data-radius="60" data-hole="0.9"></div> 
					<hr class="thin"> 
					
					</div>
					
					<!--div class="cell-md-3">
						<center>
						<ul class="sidenav-simple sidenav-simple-expand-fs h-auto">
                        <li class="active"><a href="#">
                            <span class="mif-dashboard icon"></span>
                            <span class="title">Aktuální přehled</span>
                        </a></li>
                        <li><a href="#">
                            <span class="mif-history icon"></span>
                            <span class="title">Historie</span>
                        </a></li>
                        <li><a href="#">
                            <span class="mif-power-cord icon"></span>
                            <span class="title">Napájení</span> 
                        </a></li>
                        <li><a href="#">
                            <span class="mif-wifi-connect icon"></span>
                            <span class="title">Stav sítě</span>
                        </a></li>
                            </ul></center>
					</div-->
				</div>
                <div class="row">
                    <div class="cell-md-9"  style="margin-top: 30px;"> 
				        <div id="chart_div" style="width: 600px; height: 300px"></div>
					</div>
                </div>
			</div> 
		</div> 
	</body>

<!-- jQuery first, then Metro UI JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="js/preloader.js"></script>
<script src="js/metro.js"></script>
<script type="text/javascript" src="/js/qickchart.js"></script>
<script>
	$(document).ready(function(){ 
		var reload = function(){
			        	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    var myArr = JSON.parse(this.responseText);
    //alert(myArr[0].temp);
    $('#donut_val').data('donut').val(Math.round(myArr[0].temp));
    $('#donut_va2').data('donut').val(Math.round(myArr[0].hum));
    $('#donut_va3').data('donut').val(Math.round(myArr[0].press));
    $('#donut_va4').data('donut').val(Math.round(myArr[0].power));
  }
};
	xmlhttp.open("GET", "YOUR PATH TO jsondata.php?last=1&time=" + new Date().getTime(), true);
	xmlhttp.send();
			





        	};
        	reload();

    setInterval(reload, 5000);
    });
    

</script>

</body>
</html>