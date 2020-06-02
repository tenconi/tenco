<html>
<body>
<center>
<?php
$recipiente = "jajamrockmestizo@hotmail.com.ar";
$asunto = "Comentario del Sitio";
$error = 0;
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$localidad = $_POST['localidad'];
$comentario = $_POST['comentario'];
if($nombre == "" ||  $email == "" || $comentario == ""){
   $error=1;
}
elseif(!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*" ."@"."([a-z0-9]+([\.-][a-z0-9]+)*)+"."\\.[a-z]{2,}"."$",$email)){
   $error=2;
}
if($error==1){
   echo "<b><h3>El siguiente error ha ocurrido!</h3></b><BR><br><br>";
   echo "No ha rellenado todos los campos obligatorios.<BR> Por favor vuelva <A HREF=\"javascript:history.back()\">atras</A>.<BR>";
}
elseif($error==2){
   echo "<b><h3>El siguiente error ha ocurrido!</h3></b><BR><br><br>";
   echo "El correo electronico es invalido!<BR> Por favor vuelva <A HREF=\"javascript:history.back()\">atras</A>.<BR>";
}
else{
   $message ="nombre: ".$nombre."<br>";
   $message .="email: ".$email."<br>";
   $message .="direccion: ".$direccion."<br>";
   $message .="localidad: ".$localidad."<br>";
   $message .="comentario: ".$comentario."<br>";
   $message = stripslashes($message);
   $headers = "MIME-Version: 1.0\r\n";
   $headers .= "Content-type:text/html; charset=iso-8859-1\r\n";
   $headers .= "From: $email\r\n";
   $headers .= "Repaly-to: $email\r\n";
   $headers .= "Cc: $email\r\n";
   mail($recipiente,$asunto,$message,$headers);
   echo "<b><h3>Su mensaje ha sido enviado correctamente!</h3></b><BR><br><br>";
   echo "JajAmNeZcas gracias por comentar.<BR>Nos comunicaremos con usted a la brevedad.<BR><br>";
   echo "<b>Jaj¡m le desea una muy buena jornada!</b><BR><br>";
}
?>
</center>
</body>

</html>