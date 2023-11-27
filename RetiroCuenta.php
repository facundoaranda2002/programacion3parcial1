<?php

include "cliente.php";

if(isset($_POST['Nro_Cuenta']) && isset($_POST['Tipo_de_Cuenta']) && isset($_POST['Importe_a_Retirar']))
{
    Cliente::RetirarMonto($_POST['Nro_Cuenta'], $_POST['Tipo_de_Cuenta'], $_POST['Importe_a_Retirar']);
}
    
?>