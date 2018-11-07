<?php
$url = $_GET["content"];
?>
<html>
<style>
html, body{
  height: 100%;
  margin:0;
}
iframe {
	border:0;
}
</style>
<body>
<div style='position:absolute; top:0; right:0; text-align:right; padding: 10px;'>
	<button id="report">Raportuj/schowaj</button>
	<div id="reportdiv" style='text-align:left; display:none; width:600px; border:1px solid black; border-radius:3px; padding:5px; background:white'>
		<h1>Raportowanie</h1>
		<form id="report">
		Czas błędu: <BR>
		<input name="time"><button type="button" class="timestop stop"></button><BR>
		Na kogo byłeś zalogowany (jaki admin, jaki użytkownik):<BR>
		<input name="user" style="width:100%">
		Napisz po kolei czynności, jakie wykonywałeś:<BR>
		<input type="hidden" name="url" value="<?=urlencode($_GET["content"])?>">
		<textarea style="width:100%; border:1px solid gray; border-radius:3px; padding:3px" name="steps"></textarea>
		Opisz błąd:<BR>
		<textarea style="width:100%; border:1px solid gray; border-radius:3px; padding:3px" name="description"></textarea>
		<button type="submit">Wyślij</button>
		</form>
	</div>
</div>
<iframe src="http://<?=$url?>" style="width:100%;"></iframe>
</body>
</html>
<script
			  src="https://code.jquery.com/jquery-2.2.4.min.js"
			  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
			  crossorigin="anonymous"></script>
<script>
$(document).on("load", "iframe", function(){
	$(this).append("<h1>TEST</h1>");
});
$(document).ready(function(){
	$(window).resize(function(){
		var top = $("iframe").offset().top;
		var height = $(window).height() - top;
		$("iframe").css("height", height +"px");
	}).resize();
	$("#report").click(function(){
		if($("#reportdiv").css("display")=="none")
			$("#reportdiv").slideDown();
		else {
			$("#reportdiv").slideUp();
			$(".timestop.stop").click();
		}
	});
		var interval;
	$(".timestop").click(function(){
		if($(this).hasClass("stop")) {
			interval = setInterval(function(){
				var d = new Date(Date.now());
				$("input[name='time']").val(
					d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " " + (d.getHours()<10?"0"+d.getHours():d.getHours()) + ":" + (d.getMinutes()<10?"0"+d.getMinutes():d.getMinutes()) + ":" + (d.getSeconds()<10?"0"+d.getSeconds():d.getSeconds())
				);
			}, 1000);
			$(this).html("Zatrzymaj");
			$(this).removeClass("stop");
		} else {
			$(this).addClass("stop");
			clearInterval(interval);
			$(this).html("Odliczaj ponownie");
		}
	}).click();
	
	$("form#report").submit(function(e){
		e.preventDefault();
		var form = {};
		$(this).serializeArray().map(function(x){form[x.name] = x.value;}); 
		var data = {
			"email": "marianski.jacek@gmail.com",
			"subject":"Raport",
			"body":"Url: "+form.url +"\n"
			+"Admin: <?=$_SESSION["login"]?>\n"
			+"Czas bledu: "+form["time"] +"\n"
			+"Użytkownik: "+form.user +"\n"
			+"Kroki: "+form.steps+"\n"
			+"Opis: "+form.description
		};
		$.ajax({
			url: "http://treetank.net/mails/reporter",
			method: "POST",
			data:data,
			success: function(){
				if($("#reportdiv").css("display")!="none")
					$("#report").click();
				$("form#report :input").val("");
			}
		});
	});

});
</script>