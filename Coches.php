<?php

	include 'DB.php';

	function agregarCoche($link, $matricula, $marca, $modelo, $email) {
		
		$sql = "CALL Insertar_Coche ('".$email."','".$matricula."','".$marca."','".$modelo."')";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			if (mysqli_errno($link) == 1062) {
				$data = array("errorno" => -5, "errorMessage" => "La matrícula ya existe");
			} else {
				$data = array("errorno" => -5, "errorMessage" => mysqli_error($link));
			}
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function modificarCoche($link, $matricula, $marca, $modelo ) {
		
		$passHash = password_hash($pass, PASSWORD_DEFAULT);
		$sql = "UPDATE coche SET Marca='".$marca."', Modelo='".$modelo."'
		WHERE Matricula = '".$matricula."'";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			if (mysqli_errno($link) == 1062) {
				$data = array("errorno" => -6, "errorMessage" => "La matrícula ya existe");
			} else {
				$data = array("errorno" => -6, "errorMessage" => mysqli_error($link));
			}
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}

	function leerCoches($link, $email) {
		$sql = "SELECT * FROM coche c join coches_usuario co on c.Matricula = co.MATRICULA 
		WHERE co.USUARIO = '".$email."'";
		$result = mysqli_query($link, $sql);
		$matricula;
		$marca;
		$modelo;

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			$datos;
			while($row = mysqli_fetch_assoc($result)) {
				$matricula = $row["Matricula"];
				$marca = $row["Marca"];
				$modelo = $row["Modelo"];
				$array = array("matricula" => $matricula, "marca" => $marca, "modelo" => $modelo);
				$datos[] = $array;
			}
			$data = array("errorno" => 0, "coches" => $datos);
			
		} else if (mysqli_num_rows($result) == 0){
			$data = array("errorno" => 2, "errorMessage" => "Datos incorrectos");
		} else {
			$data = array("errorno" => -7, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function leerCoche($link, $matricula, $email) {
		$sql = "SELECT * FROM coche c join coches_usuario co on c.Matricula = co.MATRICULA 
		WHERE co.USUARIO = '".$email."'";
		$result = mysqli_query($link, $sql);
		$matricula;
		$marca;
		$modelo;

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			$datos;
			while($row = mysqli_fetch_assoc($result)) {
				$matricula = $row["Matricula"];
				$marca = $row["Marca"];
				$modelo = $row["Modelo"];
				$array = array("matricula" => $matricula, "marca" => $marca, "modelo" => $modelo);
				$datos[] = $array;
			}
			$data = array("errorno" => 0, "coches" => $datos);
			
		} else if (mysqli_num_rows($result) == 0){
			$data = array("errorno" => 2, "errorMessage" => "Datos incorrectos");
		} else {
			$data = array("errorno" => -7, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function borrarCoche($link, $matricula, $email) {
		
		$sql = "CALL Borrar_Coche ('".$matricula."','".$email."')";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			$data = array("errorno" => -7, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}
	
	if(isset($_POST['action']) && (strlen($_POST['action']) > 0)) {
		$matricula = $_POST['matricula'];
		$marca;
		$modelo;
		$email;
		if(isset($_POST['marca']))
			$marca = $_POST['marca'];
		if(isset($_POST['modelo']))
			$modelo = $_POST['modelo'];
		if(isset($_POST['email']))
			$email = $_POST['email'];
		switch($_POST['action']) :
			case 'insertar':
				agregarCoche($link, $matricula, $marca, $modelo, $email);
			break;
			case 'modificar':
				modificarCoche($link, $matricula, $marca, $modelo);
            break;
			case 'leerCoches':
				leerCoches($link, $email);
            break;
            case 'leer':
				leerCoche($link, $matricula, $email);
            break;
			case 'borrar':
				borrarCoche($link, $matricula, $email);
            break;
		endswitch;
		mysqli_close($link);
		exit; # Finaliza la ejecución.
   }
	
?>