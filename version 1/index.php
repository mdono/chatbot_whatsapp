<?php
//DESHABILITAR EL MOSTRAR ERRORES
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(-1);
require 'vendor/autoload.php';

//IMPORTAR LAS LIBRERIAS DE rivescript
use \Axiom\Rivescript\Rivescript;
/**
 * VERIFICAR EL WEBHOOK
 */
// TOKEN
  $token = 'Guatemala';
//EL CHALLENGE DE FB
  $desafio = $_GET['hub_challenge'];
//TOKEN DE VERIFICACION DE FB
  $token_verificacion = $_GET['hub_verify_token'];
//VALIDAR EL TOKEN
  if ($token === $token_verificacion) {
    echo $desafio;
    exit;
  }
/**
 * Obtener mensajes de WhatsApp
 **/
$respuesta = file_get_contents("php://input");
//Convertimos el JSON en un arreglo
$respuesta = json_decode($respuesta, true);
$baseMensaje = $respuesta['entry'][0]['changes'][0]['value']['messages'][0];
$mensaje = $baseMensaje['text']['body'];
$telefono_cliente = $baseMensaje['from'];
$id = $baseMensaje['id'];
$timestamp = $baseMensaje['timestamp'];

//Verificar que el mensaje venga correcto
if ($mensaje != null) {
    $rivescript = new Rivescript();
    $rivescript->load('cursos.rive');
    //OBTENER RESPUESTA
    $respuesta = $rivescript->reply($mensaje);
    file_put_contents("./data.txt", $respuesta);
    require_once './almacena.php';
    almacena($mensaje, $respuesta, $id, $timestamp, $telefono_cliente);
}