<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root"; $contrasenia = ""; $nombreBaseDatos = "angular_trabajadores";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);


// Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
if (isset($_GET["consultarpuesto"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM puestos WHERE cla_puesto=".$_GET["consultarpuesto"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//borrar pero se le debe de enviar una clave ( para borrado )
if (isset($_GET["borrarpuesto"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"DELETE FROM puestos WHERE cla_puesto=".$_GET["borrarpuesto"]);
    if($sqlEmpleaados){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
//Inserta un nuevo registro y recepciona en método post los datos
if(isset($_GET["insertarpuesto"])){
    $data = json_decode(file_get_contents("php://input"));
    $puesto=$data->puesto;
    $sueldo=$data->sueldo;
        if(($sueldo!="")&&($puesto!="")){
            
    $sqlEmpleaados = mysqli_query($conexionBD,"INSERT INTO puestos(puesto,sueldo) VALUES('$puesto','$sueldo') ");
    echo json_encode(["success"=>1]);
        }
    exit();
}
// Actualiza datos pero recepciona datos
if(isset($_GET["actualizarpuesto"])){
    
    $data = json_decode(file_get_contents("php://input"));

    $cla_puesto=(isset($data->cla_puesto))?$data->cla_puesto:$_GET["actualizarpuesto"];
    $puesto=$data->puesto;
    $sueldo=$data->sueldo;
    
    $sqlEmpleaados = mysqli_query($conexionBD,"UPDATE puestos SET puesto='$puesto',sueldo='$sueldo' WHERE cla_puesto='$cla_puesto'");
    echo json_encode(["success"=>1]);
    exit();
}
// Consulta todos los registros de la tabla
$sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM puestos ");
if(mysqli_num_rows($sqlEmpleaados) > 0){
    $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
    echo json_encode($empleaados);
}
else{ echo json_encode([["success"=>0]]); }


?>