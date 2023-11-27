<?php
include "cliente.php";

if (isset($_GET['Tipo_De_Cuenta']) && isset($_GET['Nro_Cuenta'])) {

    Cliente::BorrarCliente($_GET['Nro_Cuenta'], $_GET['Tipo_De_Cuenta']);

} else {
    echo json_encode(['error' => 'Falta el parametro action']);
}

?>