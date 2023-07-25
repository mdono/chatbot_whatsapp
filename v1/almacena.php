<?php

/**
 * RECIBIMOS LA RESPUESTA
 */
function almacena($recibido, $enviado, $id, $timestamp, $telefono_cliente)
{
  require_once './conexion.php';
  //VARIABLE PARA VER LA CANTIDAD DE REGISTROS ENCONTRADOS
  $cantidad = 0;
  //VERIFICO A TRAVES DEL ID LOS MENSAJES
  $sql_cantidad = "SELECT COUNT(id) FROM registro WHERE id='" . $id . "';";
  $resultado_cantidad = $conn->query($sql_cantidad);

  //VEMOS SI LA CONSULTA DEVUELVE RESULTADOS
  if ($resultado_cantidad) {
    //OBTENEMOS EL PRIMER REGISTRO
    $fila_cantidad = $resultado_cantidad->fetch_row();
    $cantidad = $fila_cantidad[0];
  }

  if ($cantidad == 0) {
    //VAMOS A RESPONDER A WHATSAPP
    //TOKEN DE FB
    $token = 'TOKEN_KEY';
    //TELEFONO
    $telefono = 'TELEFONO';
    //URL A DONDE SE ENVIA EL MENSAJE
    $url = 'https://graph.facebook.com/v17.0/116542267997823/messages';
    $mensaje = ''
      . '{'
      . '"messaging_product":"whatsapp",'
      . '"recipient_type":"individual",'
      . '"to":"' . $telefono . '",'
      . '"text":'
      . '{'
      . '     "body":"' . $enviado . '",'
      . '     "preview_url":true,'
      . '}'
      . '}';;
    //DECLARAR LA CABECERA
    $header = array("Authorization: Bearer " . $token, 'Content-Type: application/json',);

    //INICIAMOS LA CURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    //OBTENER LA RESPUESTA DEL ENVIO
    $respuesta = json_decode(curl_exec($curl), true);

    //IMPRIMIENDO LA RESPUESTA
    //print_r($respuesta);

    //OBTENER EL CODIGO DE LA RESPUESTA
    $estatus_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    //CERRAMOS LA CURL
    curl_close($curl);
    //INSERTAMOS LOS REGISTROS
    $sql = "INSERT INTO registro"
      . "(mensaje_recibido, mensaje_enviado, id, timestamp, telefono)"
      . "VALUES('{$recibido}', '{$enviado}', '{$id}', '{$timestamp}', '{$telefono_cliente}')";
    $conn->query($sql);
    $conn->close();
  }
}
