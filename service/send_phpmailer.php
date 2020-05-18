<?php
	error_reporting( E_ALL );
	ini_set('display_errors', 1);
	
	setlocale(LC_ALL,"es_ES");

// RECUPERO CAMPOS DEL FORM

	$post_requestdate = date("Y-m-d H:i:s");
	$form_name = isset($_REQUEST['nombre'])? $_REQUEST['nombre'] : '';
	$form_email = isset($_REQUEST['email'])? $_REQUEST['email'] : '';
	$form_phone = isset($_REQUEST['phone'])? $_REQUEST['phone'] : '';
	$form_consulta = isset($_REQUEST['mensaje'])? $_REQUEST['mensaje'] : '';


	$message = '<html><body>';
	$message .= '<h1>DATOS INGRESADOS</h1>';
	$message .= '<table rules="all" style="border-color: #E8E8E8; width:100%" cellpadding="10" width="100%">';
	$message .= "<tr><td><strong>Enviado:</strong> </td><td>" . $post_requestdate . "</td></tr>";
	$message .= "<tr><td><strong>Nombre:</strong> </td><td>" . strip_tags($form_name) . "</td></tr>";
	$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($form_email) . "</td></tr>";
	$message .= "<tr><td><strong>Teléfono:</strong> </td><td>" . strip_tags($form_phone) . "</td></tr>";
	$message .= "<tr><td><strong>Consulta:</strong> </td><td>" . strip_tags($form_consulta) . "</td></tr>";
	$message .= "</table>";
	$message .= "</body></html>";


//CON PHPMAILER

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', '465');
define('SMTP_SECURE', 'ssl');
define('IS_HTML', true);
define('SMTPAUTH', true);// true o false, dejar false en caso de que no se necesite login
define('SMTP_USERNAME', 'mailer@e-gate.com.ar');
define('SMTP_PASSWORD', 'egate2fast');
define('SMTP_FROM', 'info@tenco.com.ar');
define('SMTP_FROMNAME', 'Contacto Web');

/*define('SMTP_HOST', 'smtp.tenco.com.ar');
define('SMTP_PORT', '25');
define('SMTP_SECURE', 'ssl');
define('IS_HTML', true);
define('SMTPAUTH', true);// true o false, dejar false en caso de que no se necesite login

define('SMTP_USERNAME', 'info@tenco.com.ar');
define('SMTP_PASSWORD', '34a2a89b'); // TU PASS
define('SMTP_FROM', 'info@tenco.com.ar');

define('SMTP_FROMNAME', 'Contacto Web');
*/
$to= "tenco216@gmail.com";
$subject = 'Contacto de ' . $form_name;
$texto = $message;
	    
	    include'../class/class.smtp.php';
	    include'../class/class.phpmailer.php';
            $mail = new phpmailer();

            $mail->CharSet = 'UTF-8';
            $mail->PluginDir = "../class/";
            $mail->Mailer = "smtp";
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPAuth = SMTPAUTH;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->IsHTML(IS_HTML);
            //$mail->SMTPDebug = 2;
            //Indicamos cual es nuestra dirección de correo y el nombre que 
            //queremos que vea el usuario que lee nuestro correo
            $mail->From = SMTP_FROM;
            $mail->FromName = SMTP_FROMNAME;

            //Indicamos cual es la dirección de destino del correo
            $mail->AddAddress($to);
		    //$mail->AddAddress('alejandro@agenciacapitan.com');

            //Asignamos asunto y cuerpo del mensaje
            $mail->Subject = $subject;
            $mail->Body = $texto;

            $sendResult = $mail->Send();

            if ($sendResult) {
                echo "OK";
            } else {
                echo "FAIL";
                echo $mail->ErrorInfo;
            }

?>
