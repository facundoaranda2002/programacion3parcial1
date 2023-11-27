<?php

include "cliente.php";

if(isset($_POST['Nro_de_Cuenta']) && isset($_POST['Tipo_de_Cuenta']))
{
    Cliente::ConsultarCliente($_POST['Nro_de_Cuenta'], $_POST['Tipo_de_Cuenta']);
}
?>