<?php

	include 'DB.php';

	function agregarUsuario($link, $email, $pass, $nombre, $apellidos, $fecha) {
		
		$passHash = password_hash($pass, PASSWORD_DEFAULT);
		$sql = "INSERT INTO usuario VALUES ('".$email."','".$passHash."','".$nombre."','".$apellidos."','".$fecha."')";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			if (mysqli_errno($link) == 1062) {
				$data = array("errorno" => -2, "errorMessage" => "La cuenta de correo ya existe");
			} else {
				$data = array("errorno" => -2, "errorMessage" => mysqli_error($link));
			}
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function modificarEmail($link, $email, $new_email) {
		
		$sql = "UPDATE usuario SET Email='".$new_email."' WHERE Email = '".$email."'";
		$data;
		
		if (!mysqli_query($link,$sql)) {
			if (mysqli_errno($link) == 1062) {
				$data = array("errorno" => -2, "errorMessage" => "La cuenta de correo ya existe");
			} else {
				$data = array("errorno" => -2, "errorMessage" => mysqli_error($link));
			}
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}

	function modificarPassword($link, $email, $pass, $new_passwd) {

		$sql = "SELECT * FROM usuario WHERE Email = '".$email."'";
		$result = mysqli_query($link, $sql);
		$em;
		$passwd;
		$nombre;
		$ap;
		$fecha;
		if (mysqli_num_rows($result) == 1) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				$email = $row["Email"];
				$passwd = $row["Password"];
				$nombre = $row["Nombre"];
				$ap = $row["Apellidos"];
				$fecha = $row["Fecha_Nac"];
			}
			if (password_verify($pass, $passwd)) {
				$passHash = password_hash($new_passwd, PASSWORD_DEFAULT);
				$sql = "UPDATE usuario SET Password='".$passHash."' WHERE Email = '".$email."'";
				$data;
				
				if (!mysqli_query($link,$sql)) {
					$data = array("errorno" => -3, "errorMessage" => mysqli_error($link));
				}
				else {
					$data = array("errorno" => 0);
				}
			} 
			else {
				$data = array("errorno" => 2, "errorMessage" => "Contraseña incorrecta");
			}
		}
		else if (mysqli_num_rows($result) == 0){
			$data = array("errorno" => 2, "errorMessage" => "Datos incorrectos");
		}
		
		$json = json_encode($data);
		echo $json;
	} 
	
	function leerUsuario($link, $email, $pass) {
		$sql = "SELECT * FROM usuario WHERE Email = '".$email."'";
		$result = mysqli_query($link, $sql);
		$em;
		$passwd;
		$nombre;
		$ap;
		$fecha;

		if (mysqli_num_rows($result) == 1) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				$email = $row["Email"];
				$passwd = $row["Password"];
				$nombre = $row["Nombre"];
				$ap = $row["Apellidos"];
				$fecha = $row["Fecha_Nac"];
			}
			if (password_verify($pass, $passwd)) {
				$data = array("errorno" => 0, "email" => $email, "nombre" => $nombre, "apellidos" => $ap, "fecha" => $fecha);
			}
			else {
				$data = array("errorno" => 1, "errorMessage" => "Contraseña incorrecta");
			}
		} else if (mysqli_num_rows($result) == 0){
			$data = array("errorno" => 2, "errorMessage" => "Datos incorrectos");
		} else {
			$data = array("errorno" => -4, "errorMessage" => "Error en la lectura de usuarios");
		}
		$json = json_encode($data);
		echo $json;
	}
	
	if(isset($_POST['action']) && (strlen($_POST['action']) > 0)) {
		$email = $_POST['email'];
		if(isset($_POST['password']))
			$pass = $_POST['password'];
		$nombre;
		$apellidos;
		$fecha;
		$new_email;
		$new_password;
		if(isset($_POST['nombre']))
			$nombre = $_POST['nombre'];
		if(isset($_POST['apellidos']))
			$apellidos = $_POST['apellidos'];
		if(isset($_POST['fecha_nac']))
			$fecha = $_POST['fecha_nac'];
		if(isset($_POST['new_email']))
			$new_email = $_POST['new_email'];
		if(isset($_POST['new_password']))
			$new_password = $_POST['new_password'];
		switch($_POST['action']) :
			case 'insertar':
				agregarUsuario($link, $email, $pass, $nombre, $apellidos, $fecha);
			break;
			case 'modificarEmail':
				modificarEmail($link, $email, $new_email);
            break;
            case 'modificarPassword':
				modificarPassword($link, $email, $pass, $new_password);
            break;
			case 'leer':
				leerUsuario($link, $email, $pass);
            break;
		endswitch;
		mysqli_close($link);
		exit; # Finaliza la ejecución.
   }
   
?>