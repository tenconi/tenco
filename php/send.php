<?php
$destino="tenco216@gmail.com";
$from="tenco@tenco.com.ar";
$nombre=$_POST["nombre"];
$correo=$_POST["correo"];
$mensaje=$_POST["mensaje"];
$contenido="Nombre: " . $nombre ."\nCorreo: " . $correo ."\nMensaje: " .$mensaje;
mail($destino, "Consulta WEB", $contenido);
header("Location:http://tenco.com.ar/#thanks");
?>