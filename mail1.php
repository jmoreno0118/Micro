<?php

include ($_SERVER['DOCUMENT_ROOT'].'/reportes/includes/phpmailer/PHPMailerAutoload.php');

  $Mail = new PHPMailer();
  $Mail->IsSMTP(); // Use SMTP
  $Mail->Host        = "smtp.gmail.com"; // Sets SMTP server
  $Mail->SMTPDebug   = 2; // 2 to enable SMTP debug information
  $Mail->SMTPAuth    = TRUE; // enable SMTP authentication
  $Mail->SMTPSecure  = "ssl"; //Secure conection
  $Mail->Port        = 465; // set the SMTP port
  $Mail->Username    = 'jj.moreno@microanalisis.com'; // SMTP account username
  $Mail->Password    = 'mor_2015'; // SMTP account password
  //$Mail->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
  //$Mail->CharSet     = 'UTF-8';
  //$Mail->Encoding    = '8bit';
  $Mail->Subject     = 'Test Email Using Gmail';
  //$Mail->ContentType = 'text/html; charset=utf-8\r\n';
  $Mail->From        = 'jj.moreno@microanalisis.com';
  $Mail->FromName    = 'GMail Test';
  $Mail->WordWrap    = 900; // RFC 2822 Compliant for Max 998 characters per line

  $Mail->AddAddress( 'jj.moreno@microanalisis.com' ); // To:
  $Mail->isHTML( TRUE );
  $Mail->Body    = "áéíóú";
  $Mail->AltBody = "áéíóú";
  $Mail->Send();
  $Mail->SmtpClose();

  if ( $Mail->IsError() ) { // ADDED - This error checking was missing
    return FALSE;
  }
  else {
    return TRUE;
  }
?>