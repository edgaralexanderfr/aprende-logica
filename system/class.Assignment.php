<?php    /**   * Copyright 2014 - Edgar Alexander Franco   *   * @author Edgar Alexander Franco   * @version 1.0.0   */    require_once $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . 'system/class.JSONCache.php';    class Assignment {    public static $PATH;    public static $ALLOWED_TYPES = array(      'pdf' => 'application/pdf',       'docx' => 'application/zip',       'doc' => 'application/msword'    );        public static $mysqli;    public $id;    public $atype;    public $publishDate;        /**     * Publish the assignment.     *     * Purges cache: assignment_download_last     *     * @param {array} $file Respective for the assignment.     */    public final function publish ($file) {      $error = $file['error'];            if ($error == UPLOAD_ERR_INI_SIZE) {        throw new Exception('La asignaci�n es muy grande.', 1);      }            if ($error == UPLOAD_ERR_NO_FILE) {        throw new Exception('No ha seleccionado ning�n archivo.', 2);      }            if ($error != UPLOAD_ERR_OK) {        throw new Exception('No se pudo publicar la asignaci�n.', 3);      }            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);            if (!isset(self :: $ALLOWED_TYPES[ $extension ])) {        throw new Exception('Extensi�n de archivo no v�lida.', 4);      }            $tmp = $file['tmp_name'];      $type = @mime_content_type($tmp);            if ($type === false) {        throw new Exception('No se pudo extraer informaci�n del archivo.', 5);      }            if ($type != self :: $ALLOWED_TYPES[ $extension ]) {        throw new Exception('Tipo de archivo no admitido.', 6);      }            $this->atype = '.' . $extension;            $mysqli = self :: $mysqli;            $atype = mysqli_real_escape_string($mysqli, $this->atype);            $query = mysqli_query($mysqli, "call assignment_publish('" . $atype . "')");            if (!$query) {        throw new Exception('Error de base de datos.', 7);      }            $data = mysqli_fetch_assoc($query);      mysqli_free_result($query);            $this->id = (int) $data['id'];      @move_uploaded_file($tmp, self :: $PATH . $this->id);            JSONCache :: purge('assignment_download_last/');    }        /**     * Download the last published assignment.     *     * Cache reference: assignment_download_last     */    public static function downloadLast () {      $cacheRef = 'assignment_download_last/';      $query = 'call assignment_download_last()';      $data = JSONCache :: get($query, $cacheRef);            if ($data === null) {        $mysqli = self :: $mysqli;        $mysqliQuery = mysqli_query($mysqli, $query);                if (!$mysqliQuery) {          throw new Exception('Error de base de datos.', 1);        }                $data = ($row = mysqli_fetch_assoc($mysqliQuery)) ? $row : array() ;        mysqli_free_result($mysqliQuery);                JSONCache :: store($query, $data, $cacheRef);      }            if ($data == null) {        throw new Exception('No hay tareas registradas para descargar.', 2);      }            $id = (int) $data['id'];      $atype = $data['atype'];      $publishDate = $data['publish_date'];            $dt = new DateTime($publishDate, new DateTimeZone($_SERVER['IN_DTZ']));      $dt->setTimezone(new DateTimeZone($_SERVER['OUT_DTZ']));      $df = $dt->format('d.m.Y h.i a');      $name = 'Asignaci�n ' . $id . ' - ' . $df . ' - Aprende L�gica' . $atype;      $path = self :: $PATH . $id;      $size = @filesize($path);            if ($size === false) {        throw new Exception('No se pudo extraer informaci�n del archivo.', 3);      }            header('Content-Description: File Transfer');			header('Content-Type: application/octet-stream');			header('Content-Disposition: attachment; filename=' . $name);			header('Content-Transfer-Encoding: binary');			header('Expires: 0');			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');			header('Pragma: public');			header('Content-Length: ' . $size);            if (@readfile($path) === false) {        throw new Exception('No se pudo leer el archivo.', 4);      }    }  }    Assignment :: $PATH = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SITE_PATH'] . 'blog/assignments/';