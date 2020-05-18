<?php
	error_reporting( E_ALL );
	ini_set('display_errors', 1);
	
	setlocale(LC_ALL,"es_ES");

// RECUPERO CAMPOS DEL FORM

	$post_requestdate = date("Y-m-d H:i:s");
	$form_name = isset($_REQUEST['name'])? $_REQUEST['name'] : '';
	$form_email = isset($_REQUEST['email'])? $_REQUEST['email'] : '';
	$form_country = isset($_REQUEST['country'])? $_REQUEST['country'] : '';
	$form_comments = isset($_REQUEST['message'])? $_REQUEST['message'] : '';
	$form_q1 = isset($_REQUEST['q1'])? $_REQUEST['q1'] : '';

// ENVIO DE MAIL DE NOTIFICACION
	$to = 'germana@lagash.com';
	$cc = 'gabrielm@lagash.com';

	$subject = 'Contact: ' . $form_name;

	$headers = "From: Formulario Web <info@lagash.com>\r\n";
	$headers .= 'Cc: '.$cc . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$message = '<html><body>';
	$message .= '<h1>Formulario de Contacto</h1>';
	$message .= '<table rules="all" style="border-color: #E8E8E8; width:100%" cellpadding="10" width="100%">';
	$message .= "<tr><td><strong>Enviado:</strong> </td><td>" . $post_requestdate . "</td></tr>";
	$message .= "<tr><td><strong>How can we help you?:</strong> </td><td>" . strip_tags($form_q1) . "</td></tr>";
	$message .= "<tr><td><strong>Name:</strong> </td><td>" . strip_tags($form_name) . "</td></tr>";
	$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($form_email) . "</td></tr>";
	$message .= "<tr><td><strong>Country:</strong> </td><td>" . strip_tags($form_country) . "</td></tr>";
	$message .= "<tr><td><strong>Message:</strong> </td><td>" . strip_tags($form_comments) . "</td></tr>";
	$message .= "</table>";
	$message .= "</body></html>";

$bool =	mail($to, $subject, $message, $headers);

if($bool){
    echo "Mensaje enviado a "+$to;
}else{
    echo "Mensaje no enviado";
}

?>