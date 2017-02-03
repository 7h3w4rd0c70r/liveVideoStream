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
<title>Daxni.cz &middot; GO LIVE...</title>

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
	
		<h2>Vysílat</h2>
		
		<div class="controlls_goLive">
		
			<table>
				<tr>
					<td><button class="live_start" id="open">Zahájit vysílání</button></td>
					<td><button class="live_end" id="close">Ukončit vysílání</button></td>
				</tr>
			</table>
			<div class="clrBth"></div>
			<div></div>
		
		</div>
		
		<div class="live_webCam" id="myVideo">
			<center>
				Nevysíláte žádný stream.
			</center>
		</div>
		
		<div class="live_stats">
		
			<div class="live_online_users">
			
				<table>
					<tr class="total_online boldText">
						<td>Celkem online: 10</td>
					</tr>
					<tr>
						<td>Uživatel 1</td>
					</tr>
					<tr>
						<td>Uživatel 2</td>
					</tr>
					<tr>
						<td>Uživatel 3</td>
					</tr>
					<tr>
						<td>Uživatel 4</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
					<tr>
						<td>Uživatel 5</td>
					</tr>
				</table>
			
			</div>
			<div class="chatBox">
				<div id="chatWindow" style="width: 100%; height: 100%/*calc(100% - 24px)*/; overflow-y: scroll;"></div>
			</div>
			<div class="clrBth"></div>
			<div></div>
		
		</div>
	
	</div> <!-- end content -->

</div> <!-- end main -->

<? include('footer_auth.php'); ?>

<script>
            var connection = new RTCMultiConnection();
            connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';
            connection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: false,
                OfferToReceiveVideo: false
            };
            connection.onstream = function(e) {
                $('#myVideo').html(e.mediaElement);
            };
            connection.onmessage = function (e) {
                $('#chatWindow').append('<p><b>' + (e.data.nickname == '!!!ADMIN' ? '<span style="color: blue;">Prezentér: </span>' : e.data.nickname + ': ') + '</b><span>' + e.data.message + '</span></p>');
                $('#chatWindow').animate({scrollTop: $('#chatWindow').prop('scrollHeight')}, 250);
            };
            var room = 'HOST__' + String(Math.round(Math.random() * 10000000));
            $('#send').prop('disabled', true);
            $('#close').prop('disabled', true);
            $('#open').click(function() {
                $('#open').prop('disabled', true);
                $('#close').prop('disabled', false);
                $('#send').prop('disabled', false);
                connection.session = {
                    audio: true,
                    video: true,
                    data: true
                };
                connection.open(room);
				io.emmit('NEW HOST', room);
            });
            $('#close').click(function() {
                $('#open').prop('disabled', false);
                $('#close').prop('disabled', true);
                $('#send').prop('disabled', true);
                connection.close();
                window.location.reload();
            });
            /*$('#send').click(function () {
                var msg = $('#message').val();
                $('#message').val('');
                connection.send({
                    nickname: '!!!ADMIN',
                    message: msg
                });
                $('#chatWindow').append('<p><b><span style="color: blue;">Prezentér: </span></b><span>' + msg + '</span></p>');
                $('#chatWindow').animate({scrollTop: $('#chatWindow').prop('scrollHeight')}, 250);
            });*/
        </script>

</body>
</html>