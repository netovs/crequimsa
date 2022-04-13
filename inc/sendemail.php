<?php

// Part to be executed if FORM has been submitted
  if ( isset( $_REQUEST['url'] ) && $_REQUEST['url'] != 'http://' || $_REQUEST['url'] != 'https://' ) {

  $secret = '6Lcbsu4UAAAAAJ82m07saxT-tMpG-I_gkepHi32i';
  $response = $_REQUEST['g-recaptcha-response'];

  /*
  echo $response . '<pre>';
  print_r($_REQUEST);
  echo '</pre>';
  */

  $datos = array(
  'secret' => $secret,
  'response' => $response
  );

  $verify = curl_init();
  curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
  curl_setopt($verify, CURLOPT_POST, true);
  curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($datos));
  curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($verify);

  // print_r($response);

  $response = json_decode($response, true);
  if($response["success"] === true)
  {
    // Define some constants
    define( "RECIPIENT_NAME", "Jesus Alberto Sanchez" );
    define( "RECIPIENT_EMAIL", "a.sanchezcq@gmail.com" );

    // Read the form values
    $success = false;
    $name = isset( $_POST['name'] ) ? preg_replace( "/[^\.\-\' a-zA-Z0-9]/", "", $_POST['name'] ) : "";
    $senderEmail = isset( $_POST['email'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['email'] ) : "";
    $phone = isset( $_POST['phone'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone'] ) : "";
    $services = isset( $_POST['services'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['services'] ) : "";
    $subject = isset( $_POST['subject'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['subject'] ) : "";
    $website = isset( $_POST['website'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['website'] ) : "";
    $message = isset( $_POST['message'] ) ? preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'] ) : "";

    $mail_subject = 'A contact request send by ' . $name;

    $body = 'Nombre: '. $name . "\r\n";
    $body .= 'Email: '. $senderEmail . "\r\n";


    if ($phone) {$body .= 'Telefono: '. $phone . "\r\n"; }
    if ($services) {$body .= 'services: '. $services . "\r\n"; }
    if ($subject) {$body .= 'Subject: '. $subject . "\r\n"; }
    if ($website) {$body .= 'Website: '. $website . "\r\n"; }

    $body .= 'Mensaje: ' . "\r\n" . $message;



    // If all values exist, send the email
    if ( $name && $senderEmail && $message ) {
    $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
    $headers = "From: " . $name . " <" . $senderEmail . ">";
    $success = mail( $recipient, $mail_subject, $body, $headers );
      echo "<div class='inner success'><p class='success'>¡GRACIAS POR ESCRIBIRNOS! PRONTO ESTAREMOS EN CONTACTO.</p></div>";
    } else {
      echo "<div class='inner error'><p class='error'>¡ALGO SALIÓ MAL, POR FAVOR VUELVE A INTENTAR!</p></div>";
    }
  }

  else {

  $errorMessage = "<div class='inner error'><p class='error'>¿Eres un robot que no se fijó que hay reCaptcha?</div>";

  $responseArray = array('type' => 'danger', 'message' => $errorMessage);

  $encoded = json_encode($responseArray);

  header('Content-Type: application/json');

  echo $encoded;

  }
}

?>
