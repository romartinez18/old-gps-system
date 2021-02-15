<?php

function acortarTexto($string, $length=NULL)
{
    //Si no se especifica la longitud por defecto es 50
    if ($length == NULL)
        $length = 30;
    //Primero eliminamos las etiquetas html y luego cortamos el string
    $stringDisplay = substr(strip_tags($string), 0, $length);
    //Si el texto es mayor que la longitud se agrega puntos suspensivos
    if (strlen(strip_tags($string)) > $length)
        $stringDisplay .= ' ...';
    return $stringDisplay;
}

function setList($id, $lista) {
	if (isset($GLOBALS['listas'][$id])) { unset($GLOBALS['listas'][$id]);}
	
  $GLOBALS['listas'][$id] = $lista;
}

function setIgnoreList($id, $lista) {
	if (!isset($GLOBALS['listas'][$id])) { $GLOBALS['listas'][$id] = $lista; }
}

function getList($id) {
	if (isset($GLOBALS['listas'][$id])) { return $GLOBALS['listas'][$id];}
	
  return "";
}

?>