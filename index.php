<?php


//var_dump($_POST);

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        include "ConsultarMovimientos.php";
        break;
    case 'POST':
        switch ($_POST['accion'])
        {
            case 'alta':
                include "CuentaAlta.php";
                break;
            case 'consultar':
                include "ConsultarCuenta.php";
                break;
            case 'depositar':
                include "DepositoCuenta.php";
                break;
            case 'retirar':
                include "RetiroCuenta.php";
                break;
            case 'ajustar':
                include "AjusteCuenta.php";
                break;
            default:
                echo 'Accion no permitida';
                break;
        }
        break;
    case 'PUT':
        include "ModificarCuenta.php";
        break;
    case 'DELETE':
        include "BorrarCuenta.php";
        break;
    default:
        echo 'Verbo no permitido';
        break;
}

?>