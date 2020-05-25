<?php
$destino="tenco216@gmail.com";
$nombre=$_POST["nombre"];
$correo=$_POST["correo"];
$mensaje=$_POST["mensaje"];
$contenido="Nombre: " . $nombre ."\nCorreo: " . $correo ."\nMensaje: " .$mensaje;
mail($destino, "Consulta WEB", $contenido);
header(echo "Muchas gracias por el texto";)

?>