<?

	session_start();
	
	if($_SESSION['logged'] != "YES") { header("Location:login.php"); }
	
	include('api/functions.php');

?>
<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<meta charset="UTF-8" />
<meta name="format-detection" content="telephone=no" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="3ANGLE Studio" />
<meta name="robots" content="INDEX,FOLLOW" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=2, user-scalable=1" />
<meta property="og:title" content="" />
<meta property="og:type" content="website" />
<meta property="og:url" content="/" />
<meta property="og:image" content="" />
<title>Daxni.cz &middot; LIVE...</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.5/waypoints.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?callback=initMap&key=AIzaSyDGUCL9HnJoITXEPmLEhSWvO53rB_q_r5k" async defer></script>
        <script src="https://rtcmulticonnection.herokuapp.com/dist/RTCMultiConnection.min.js"></script>
        <script src="https://rtcmulticonnection.herokuapp.com/socket.io/socket.io.js"></script>

<script src="header.js"></script>

<style>
	video {
		width: 100%;
		height: 100%;
		border-radius: 10px;
	}
</style>

</head>
<body>

<? include('header.php'); ?>

<div class="main">

	<div class="content">
	
		<h2>Název streamu</h2>
		<div class="live_assets">
			<div class="live_box_video" id="video"></div>
			<div class="live_chat">
				<div id="chatWindow" style="width: 100%; height: calc(100% - 20px); overflow-y: scroll;"></div>
            	<input type="text" id="message" placeholder="Zpráva" /><button id="send">Odeslat zprávu</button>
			</div>
			<div class="clrBth"></div>
			<div></div>
		</div>
	
	</div> <!-- end content -->

</div> <!-- end main -->

<div class="footer">

	<div class="content">
	
		<div class="section1">
		
			<table>
			
				<tr>
					<td><span>PRAVIDLA</span></td>
					<td><span>CO NABÍZÍME</span></td>
					<td><span>JAK VŠE FUNGUJE</span></td>
				</tr>
			
			</table>
		
		</div>
		<div class="section2">
		
			<table>
				<tr>
					<td><span class="selected">Čeština</span></td>
					<td><span>English(US)</span></td>
					<td><span>Slovenčina</span></td>
					<td><span>Pусский</span></td>
				</tr>
			</table>
		
		</div>
	
	</div> <!-- end content -->

</div> <!-- end footer -->

<script>
            window.getParam = function(n){var r=null,t=[];location.search.substr(1).split("&").forEach(function(i){t=i.split("=");if(t[0]===n)r=decodeURIComponent(t[1]);});return r;}
            
            $(document).ready(function () {
                var connection = new RTCMultiConnection();
				var io = new io({socketURL: 'http://89.221.210.229:8090/'});
                connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';
                connection.session = {
                    audio: false,
                    video: false,
                    data: true
                };
                connection.sdpConstraints.mandatory = {
                    OfferToReceiveAudio: true,
                    OfferToReceiveVideo: true
                };
                connection.onstream = function(e) {
                    $('#video').append(e.mediaElement);
                };
                connection.onstreamended = function (e) {
                    $('#video').html('<h1>Živý stream byl ukončen.</h1>');
                    window.location.href = './index.html';
                };
                connection.onmessage = function (e) {
                    $('#chatWindow').append('<p><b>' + (e.data.nickname == '!!!ADMIN' ? '<span style="color: blue;">Prezentér: </span>' : e.data.nickname + ': ') + '</b><span>' + e.data.message + '</span></p>');
                    $('#chatWindow').animate({scrollTop: $('#chatWindow').prop('scrollHeight')}, 250);
                };
                var room = getParam('host'),
                    nickname = getParam('nickname');
                connection.checkPresence(room, function (exists) {
                    if (exists) {
                        connection.join(room);
                        $('#send').prop('disabled', false);
                    } else {
                        $('#video').html('<h1>Zadaný stream nebyl nalezen.</h1>');
                        $('#send').prop('disabled', true);
                    }
                });
                $('#send').click(function () {
                    var msg = $('#message').val();
                    $('#message').val('');
                    connection.send({
                        nickname: nickname,
                        message: msg
                    });
                    $('#chatWindow').append('<p><b>' + nickname + ': </b><span>' + msg + '</span></p>');
                    $('#chatWindow').animate({scrollTop: $('#chatWindow').prop('scrollHeight')}, 250);
                });
            });
        </script>

</body>
</html>