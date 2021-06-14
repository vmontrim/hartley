<?php
$secretkey = "6Lf6X9waAAAAAP5T8de3obvp2ADM98AXatFjJBwK";
$msg = '';
header('Content-Type: application/json');

function sendMessage($data){
    

	$name = $data['name'];
	$mailFrom = $data['email'];
	$message = $data['comment'];
	$subject = "Hartley Service Centre";

	$mailTo = "rokas@padarom.lt";
	
	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= 'From: '.$name.' <'.$mailFrom.'>'."\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion();

	$txt = "<html><body><p>You have received and e-mail from <b>".$name."</b>:</p>\n\n<p style=\"padding-top: 15px;\">".$message.'</p></body></html>';

    if(mail($mailTo, $subject, $txt, $headers)) {
		echo json_encode([
			'status' => true,
			'message' => 'Your message has been sent!'
		]);
		exit();
    }
	
	echo json_encode([
		'status' => false,
		'message' => 'Something went wrong, go back and try again!'
	]);
    
}



$json = file_get_contents('php://input');
// Converts it into a PHP object 
$data = json_decode($json, true);

if(empty($data)){
	echo json_encode([
		'status' => false,
		'message' => '3.'
	]);
	exit();
}


$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$data['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);

if(($data['g-recaptcha-response']) && !empty($data['g-recaptcha-response'])) {

	$responseData = json_decode($response);

	if($responseData->success) {
		sendMessage($data);
	}else {
		echo json_encode([
			'status' => false,
			'message' => 'Are you sure you\'re not a robot?'
		]);
		exit();
	}
	

}else{
	echo json_encode([
		'status' => false,
		'message' => 'There was an error, please try again later.'
	]);
	exit();
}


