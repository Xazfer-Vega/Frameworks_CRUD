<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root"; $contrasenia = ""; $nombreBaseDatos = "angular_trabajadores";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);


// Consulta datos y recepciona una clave para consultar los datos bajo la clave
if (isset($_GET["consultarempleado"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT nombre, cla_puesto FROM empleados, puestos WHERE empleados.cla_puesto = puestos.cla_puesto AND empleados.clave=".$_GET["consultarempleado"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


// Actualiza datos pero recepciona datos
if(isset($_GET["actualizarempleado"])){
    
    $data = json_decode(file_get_contents("php://input"));

    $clave=(isset($data->clave))?$data->clave:$_GET["actualizarempleado"];
    $nombre=$data->nombre;
    $cla_puesto=$data->cla_puesto;
    
    $sqlEmpleaados = mysqli_query($conexionBD,"UPDATE empleados SET nombre='$nombre',cla_puesto='$cla_puesto' WHERE clave='$clave'");
    echo json_encode(["success"=>1]);
    exit();
}

//Inserta un nuevo registro y recepciona en método post los datos
if(isset($_GET["insertarempleado"])){
    $data = json_decode(file_get_contents("php://input"));
    $nombre=$data->nombre;
    $cla_puesto=$data->cla_puesto;
        if(($nombre!="")&&($cla_puesto!="")){
            
    $sqlEmpleaados = mysqli_query($conexionBD,"INSERT INTO empleados(nombre,cla_puesto) VALUES('$nombre','$cla_puesto') ");
    echo json_encode(["success"=>1]);
        }
    exit();
}

//borrar pero se le debe de enviar una clave ( para borrado )
if (isset($_GET["borrarempleado"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"DELETE FROM empleados WHERE clave=".$_GET["borrarempleado"]);
    if($sqlEmpleaados){
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}
// Consulta todos los registros de la tabla
$sqlEmpleaados = mysqli_query($conexionBD,"SELECT empleados.clave, empleados.nombre, puestos.puesto, puestos.sueldo FROM empleados, puestos WHERE empleados.cla_puesto = puestos.cla_puesto");
if(mysqli_num_rows($sqlEmpleaados) > 0){
    $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
    echo json_encode($empleaados);
}
else{ echo json_encode([["success"=>0]]); }


?>