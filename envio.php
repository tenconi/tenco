<?php
/*
 * Reemplazar destino@www.com.ar por la direccion de mail donde se desea recibir el formulario y desde@www.com.ar
 * por la direccion que debe aparecer como la direccion que envia el formulario.
 */
$recipient = 'alejandromarsico@gmail.com';
$from = 'cONTACTO wEB';/* 'destino@www.com.ar' */


/*
 * No modificar el siguiente codigo. Dejar intacto para un correcto funcionamiento.
 */



//
// clean internal variables..
//
if(!extension_loaded('templates'))
    #@dl('templates.so');

$noPrint = array('recipient', 'required',  'mailname', 'email', 'subject', 'redirect', 'submit');

$subject = '';
$sender = '';
$realname = '';
$redirect = '';
$require = '';
$postString = '';
$fields = '';
$tmp = '';


//
// check how to get vars from form.
//
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    while(list($key, $val) = each($_POST)) {

        $key = stripslashes($key);
        $key = urlencode($key);

	// if $val is an array, transforms it to name=val&name=val2..
	if(is_array($val)) {
	    
	    foreach($val as $val2) {	    

		$val2 = stripslashes($val2);
		$val2 = urlencode($val2);
		$postString .= "$key=$val2&";

	    }
	    
	} else {

	    $val = stripslashes($val);
	    $val = urlencode($val);
	    $postString = "$key=$val&";
	
	}
	
	$tmp .= $postString;
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET'){

    // if METHOD == GET, dont touch it.
    $tmp = $_SERVER['QUERY_STRING'];

} else {

    @send_error('bad_method');

}


//
// trims last '&'
//
$tmp = substr($tmp, 0, (strlen($tmp)-1));


//
// variable splittin'
//
$vars = explode('&', $tmp);


//
// transforms variables list to $fields array.
// $fields['field_name'] == field_value
//
foreach ($vars as $pair) {

	list($name, $value) = explode("=", $pair);

	$name = htmlspecialchars(urldecode($name));
	$value = htmlspecialchars(urldecode($value));

	if( $fields[$name] != '') {

	    $fields[$name] = $fields[$name] . ", $value";

	} else {

	    $fields[$name] = $value;

	}
}


//
// if there is no recipient, send it to an error page.
//

if($recipient == '' || $recipient == 'tenco@tenco.com.ar') { /* 'destino@www.com.ar' */
	@send_error('no_recipient');
}

//
// check for required fields
//
if(isset($fields['required'])) {
	
	$require = explode(',', str_replace(' ', '', $fields['required']));

	foreach ($require as $tmp) {

		if($fields[$tmp] == '') {
			if($errors_required) {
				$errors_required .= ', ' . $tmp;
			} else {
				$errors_required = $tmp;
			}
		}

	}
}


//
// if there is required fields that aren't filled in, send it to an error page.
//
if($errors_required)
	@send_error('required_fields');


//
// if there is not subject defined in form, use 'WWW.COM.AR FormSender'
//
if(!isset($fields['subject'])) {

    $subject = "Comentario Web\r\n"; /* ==== ↕ ↕ ↕ ↕  SUBJECT >>> MODIFICARLO ↕ ↕ ↕ ↕ ==== "WWW.COM.AR FormSender\r\n" */

} else {

    $subject = $fields['subject'];

}

//
// makes messsage 
//


$fecha = str_replace("\n", '', date(' d / m / Y '));
$hora = str_replace("\n", '', date(' H:i '));



if(extension_loaded('templates') && $template = tmpl_open('./tmpl.html')) {

    tmpl_set($template, 'titulo', $subject);
    tmpl_set($template, 'fecha', $fecha);    
    tmpl_set($template, 'hora', $hora);
        
    tmpl_context($template, 'row');

    foreach (array_keys($fields) as $field) {

	$field_name = str_replace('_', ' ', $field);
	if(in_array($field_name, $noPrint))
	    continue;

	if($fields[$field] == '') 
	    $fields[$field] = 'No Ingresado';

        tmpl_iterate($template, '/row');

	tmpl_set($template, 'campo', $field_name);
        tmpl_set($template, 'valor', $fields[$field]);

    }

    $parsed = @tmpl_parse($template);

} else {/* ==== ↕ ↕ ↕ Agregar campos para que llegue el mensaje ↕ ↕ ↕ ==== */

    $parsed = "Enviado el dia: " .$fecha. " a las " .$hora. "<br><br>\n";

    foreach (array_keys($fields) as $field) {

	$field_name = str_replace('_', ' ', $field);
	if(in_array($field_name, $noPrint))
	    continue;

    
        if(str_replace(' ', '', $fields[$field]) == '')

	    $fields[$field] = 'No Ingresado';
    
	$parsed .= $field_name . ': ' . $fields[$field] . "<br>" . "\n";

    }

}



print_r($vars);

//
// sets email header.
//
$header = "MIME-Version: 1.0\n";
$header .= "Content-type: text/html; charset=\"iso-8859-1\"\n";
$header .= "From: " . $from . "\n";

//
// ..sends mail..
//
if(!@mail($recipient, $subject, $parsed, $header)) {
    if($recipients_error != '') {
        $recipients_error = ', ' . $recipient;
    } else { 
        $recipients_error = $recipient;
    }
}

if($recipients_error != '') {
    @send_error('cant_send');
}



//
// ..check if theres a thx page..redirects to it..
//
if(isset($fields['redirect'])) {

    $tmp = 'Location: ' . $fields['redirect'] . "\n";
    header($tmp);
    die();    

} else {

    @send_error('no_greets');

}


//
// error function, it sends the user to an error page depending on its argument
//
function send_error($error) {

	global $errors_required;

	switch ($error) {
	
		case 'required_fields':
			$html = file('err_required.html');
			foreach ($html as $line) {
				if(stristr($line, '<% required_fields %>'))
//					str_replace('<% required_fields %>', $errors_required, $line);
				    $line = ereg_replace('<% required_fields %>', $errors_required, $line);
				print $line;
			}
			die();
			break;
		case 'no_recipient':
			$html = file('err_recipient.html');
			foreach ($html as $line ) {
				print $line;
			}
			die();
			break;
		case 'too_many_recipients':
			$html = file('err_too_many_recipients.html');
			foreach ($html as $line) {
			    print $line;
			}
			die();
			break;
		case 'bad_method':
			$html = file('err_method.html');
			foreach ($html as $line ) {
				print $line;
			}
			die();
			break;
		case 'cant_send':
			$html = file('err_cantsend.html');
			foreach ($html as $line ) {
				print $line;
			}
			die();
			break;
		case 'no_greets':
			$html = file('err_nogreets.html');
			foreach ($html as $line ) {
				print $line;
			}
			die();
			break;
		case 'no_mailtmpl':
			$html = file('err_notmpl.html');
			foreach ($html as $line ) {
				print $line;
			}
			die();
			break;
		default:
			$html = file('err_unknown.html');
			foreach ($html as $line ) {
				print $line;
			}
			die();
			break;	
	}
}

?>
