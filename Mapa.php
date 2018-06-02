<?php

	include 'DB.php';

	function agregarPosicion($link, $matricula, $longitud, $latitud) {
		
		$sql = "CALL Insertar_Posicion ('".$matricula."','".$longitud."','".$latitud."')";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			$data = array("errorno" => -8, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function modificarPosicion($link, $matricula, $longitud, $latitud ) {
		
		$sql = "UPDATE mapa SET Longitud='".$longitud."', Latitud='".$latitud."'
		WHERE Matricula = '".$matricula."'";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			$data = array("errorno" => -9, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}

	function leerPosicionesUsuario($link, $email) {
		$sql = "SELECT * FROM mapa m join coches_usuario co on m.Coche = co.MATRICULA 
		WHERE co.USUARIO = '".$email."'";
		$result = mysqli_query($link, $sql);
		$matricula;
		$longitud;
		$latitud;

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			$datos;
			while($row = mysqli_fetch_assoc($result)) {
				$matricula = $row["Coche"];
				$longitud = $row["Longitud"];
				$latitud = $row["Latitud"];
				$array = array("longitud" => $longitud, "latitud" => $latitud);
				$coordenadas[] = $array; 
				$array = array("matricula" => $matricula, "coordenadas" => $coordenadas);
				$datos[] = $array;
			}
			$data = array("errorno" => 0, "coches" => $datos);
			
		} else if (mysqli_num_rows($result) == 0){
			$data = array("errorno" => 2, "errorMessage" => "Datos incorrectos");
		} else {
			$data = array("errorno" => -10, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function leerPosiciones($link, $matricula) {
		$sql = "SELECT * FROM mapa m join coches_usuario co on m.Coche = co.MATRICULA 
		WHERE co.MATRICULA = '".$matricula."'";
		$result = mysqli_query($link, $sql);
		$matricula;
		$longitud;
		$latitud;

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			$datos;
			while($row = mysqli_fetch_assoc($result)) {
				$matricula = $row["Coche"];
				$longitud = $row["Longitud"];
				$latitud = $row["Latitud"];
				$array = array("matricula" => $matricula, "longitud" => $longitud, "latitud" => $latitud);
				$datos[] = $array;
			}
			$data = array("errorno" => 0, "coches" => $datos);
			
		} else if (mysqli_num_rows($result) == 0){
			$data = array("errorno" => 2, "errorMessage" => "Datos incorrectos");
		} else {
			$data = array("errorno" => -10, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		$json = json_encode($data);
		echo $json;
	}
	
	function borrarPosicion($link, $matricula) {
		
		$sql = "DELETE FROM mapa where COCHE = '".$matricula."'";
		
		$data;
		
		if (!mysqli_query($link,$sql)) {
			$data = array("errorno" => -11, "errorMessage" => "El servidor tiene problemas en estos momentos");
		}
		else {
			$data = array("errorno" => 0);
		}
		$json = json_encode($data);
		echo $json;
	}
	
	if(isset($_POST['action']) && (strlen($_POST['action']) > 0)) {
		$matricula = $_POST['matricula'];
		$longitud;
		$latitud;
		$email;
		if(isset($_POST['longitud']))
			$longitud = $_POST['longitud'];
		if(isset($_POST['latitud']))
			$latitud = $_POST['latitud'];
		if(isset($_POST['email']))
			$email = $_POST['email'];
		switch($_POST['action']) :
			case 'insertar':
				agregarPosicion($link, $matricula, $longitud, $latitud);
			break;
			case 'modificar':
				modificarPosicion($link, $matricula, $longitud, $latitud);
            break;
			case 'leer':
				leerPosiciones($link, $matricula);
            break;
            case 'leerPorUsuario':
            	leerPosicionesUsuario($link, $email);
            break;
			case 'borrar':
				borrarPosicion($link, $matricula);
            break;
		endswitch;
		mysqli_close($link);
		exit; # Finaliza la ejecución.
   }
	
?>