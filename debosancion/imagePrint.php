<?php
if (isset($_REQUEST['file']) && isset($_REQUEST['desc'])) {
    $files = explode(";", $_REQUEST['file']);
    foreach ($files as $k => $file) {
        $sub = explode("-", str_replace("sancion_", "", $file));
        $sub = floatval($sub[0]);
        $sub = ($sub < 2017 ? $sub . '/' : '');
        $file = 'fotos/' . $sub . $file;
        $date = date('d-m-Y');
        $files[$k] = array(
            'nombre' => $file,
            'fecha' => $date
        );
    }
    $desc = $_REQUEST['desc'];
} else {
    die;
}
?>
<html>
    <head>
	<meta charset='UTF-8'/>
        <script type="text/javascript">window.print();</script>
        <style>
            @page { margin:15px;}
            #todo{text-align: center !important;}
        </style>
    </head>
    <div id="todo">
        <?php foreach ($files as $file) { ?>
        <img src="<?php echo $file['nombre']; ?>" />
        <br>
        <label><?php echo $file['fecha']; ?></label>
        <br><br>
        <?php } ?>
        <label><?php echo $desc; ?></label>
    </div>
</html>