<?php

include "cliente.php";

if(isset($_POST['Nro_Deposito_o_Extraccion']) && isset($_POST['Motivo']) && isset($_POST['Monto']))
{
    Cliente::AjusteCuenta($_POST['Monto'], $_POST['Motivo'], $_POST['Nro_Deposito_o_Extraccion']);
}
    
?>