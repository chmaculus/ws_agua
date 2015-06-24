<?php

$fecha = $FEC_TOMA;
$ano = substr($fecha, 6, 4);
$mes = substr($fecha, 3, 2);
$dia = substr($fecha, 0, 2);
$hora = substr($fecha, 11, 5);
$FEC_TOMA = $ano . $mes . $dia . " " . $hora;
?>
