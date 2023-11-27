<?php

include "cliente.php";

//LOS PUT SE PONEN EN EL X-WWW-FORM-URLENCODED  

parse_str(file_get_contents("php://input"), $putData);


if(isset($putData['Nombre']) && isset($putData['Apellido']) && isset($putData['Tipo_Documento']) 
&& isset($putData['Nro_Documento']) && isset($putData['Email']) && isset($putData['Tipo_de_Cuenta']) && isset($putData['Nro_Cuenta'])) 
{
    $cliente = new Cliente($putData['Nombre'],$putData['Apellido'],$putData['Tipo_Documento'],$putData['Nro_Documento'],$putData['Email'],$putData['Tipo_de_Cuenta'],0,$putData['Nro_Cuenta']);
    Cliente::ModificarCuenta($cliente);
} 
else 
{
    // echo json_encode(['error' => 'Faltan parametros']);
    echo "Faltan parametros";
}


?>