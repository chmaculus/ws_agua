<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<title>Entrar los datos</title>
</head>

<body>
	<form action="encriptador.php" method="post">
		<label for='usuario'>USUARIO: </label>
		<input type='text' name='log' id='usuario'/>
    </p>
     
    <p>
		<label for='contrasena'>CONTRASEÑA: </label>
		<input type='password' name='pass' id='contrasena'/>
    </p>
	
	<p>
		<label for='servidor'>SERVIDOR: </label>
		<input type='text' name='serv' id='servidor'/>
    </p>
	
	<p>
		<label for='basedatos'>BASE DE DATOS: </label>
		<input type='text' name='base' id='basedatos'/>
    </p>
	
	<p>
		<label for='pfscampo'>Num del FPS que generar: </label>
		<input type='text' name='pfs' id='pfscampo'/>
    </p>
	
	<p>
			<input type="submit" name="submit" value="¡Encriptar ya!"/>
    </p>
	
	
</body>
</html>