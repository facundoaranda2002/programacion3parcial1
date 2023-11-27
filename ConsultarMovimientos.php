<?php

// include "deposito.php";
// include "retiro.php";
include "cliente.php";

if(isset($_GET['Tipo_Listado']))
{
    switch ($_GET['Tipo_Listado'])
    {
        //PODRIA HACER UNA UNICA FUNCION DE LISTADO QUE VARIE LO QUE HACE SEGUN LOS PARAMETROS QUE RECIBE TAMBIEN
        case 'a':
            if(isset($_GET['Tipo_De_Cuenta']))
            {
                if(isset($_GET['Fecha']))
                {
                    $monto = Deposito::TotalDepositado($_GET['Tipo_De_Cuenta'], $_GET['Fecha']);
                }
                else
                {
                    $monto = Deposito::TotalDepositado($_GET['Tipo_De_Cuenta']);
                }
                echo "Total monto: " . $monto;
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'b':
            if(isset($_GET['Nro_Cuenta']))
            {
                $arrayDepositos = Deposito::BuscarDepositoParticular($_GET['Nro_Cuenta']);
                $arrayActivas = Cliente::MostrarActivas($arrayDepositos);
                Deposito::MostrarArrayDepositos($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'c':
            if(isset($_GET['Fecha_Inicial']) && isset($_GET['Fecha_Final']))
            {
                $arrayDepositos =Deposito::BuscarEntreFechas($_GET['Fecha_Inicial'],$_GET['Fecha_Final']);
                $arrayActivas = Cliente::MostrarActivas($arrayDepositos);
                Deposito::MostrarArrayDepositos($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'd':
            if(isset($_GET['Tipo_De_Cuenta']))
            {
                $arrayDepositos = Deposito::BuscarPorTipoDeCuenta($_GET['Tipo_De_Cuenta']);
                $arrayActivas = Cliente::MostrarActivas($arrayDepositos);
                Deposito::MostrarArrayDepositos($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'e':
            if(isset($_GET['Moneda']))
            {
                $arrayDepositos = Deposito::BuscarPorMoneda($_GET['Moneda']);
                $arrayActivas = Cliente::MostrarActivas($arrayDepositos);
                Deposito::MostrarArrayDepositos($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'f':
            if(isset($_GET['Tipo_De_Cuenta']))
            {
                if(isset($_GET['Fecha']))
                {
                    $monto = Retiro::BuscarTotalRetirado($_GET['Tipo_De_Cuenta'], $_GET['Fecha']);
                }
                else
                {
                    $monto = Retiro::BuscarTotalRetirado($_GET['Tipo_De_Cuenta']);
                }
                echo "Total monto: ". $monto;
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'g':
            if(isset($_GET['Nro_Cuenta']))
            {
                $arrayRetiro = Retiro::BuscarRetiroParticular($_GET['Nro_Cuenta']);
                $arrayActivas = Cliente::MostrarActivas($arrayRetiro);
                Retiro::MostrarArrayRetiro($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'h':
            if(isset($_GET['Fecha_Inicial']) && isset($_GET['Fecha_Final']))
            {
                $arrayRetiro =Retiro::BuscarRetiroEntreFechas($_GET['Fecha_Inicial'],$_GET['Fecha_Final']);
                $arrayActivas = Cliente::MostrarActivas($arrayRetiro);
                Retiro::MostrarArrayRetiro($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'i':
            if(isset($_GET['Tipo_De_Cuenta']))
            {
                $arrayRetiro = Retiro::BuscarPorTipoDeCuentaRetiro($_GET['Tipo_De_Cuenta']);
                $arrayActivas = Cliente::MostrarActivas($arrayRetiro);
                Retiro::MostrarArrayRetiro($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'j':
            if(isset($_GET['Moneda']))
            {
                $arrayRetiro = Retiro::BuscarPorMonedaRetiro($_GET['Moneda']);
                $arrayActivas = Cliente::MostrarActivas($arrayRetiro);
                Retiro::MostrarArrayRetiro($arrayActivas);
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        case 'k':
            if(isset($_GET['Nro_Cuenta']))
            {
                $arrayDepositos = Deposito::BuscarDepositoParticular($_GET['Nro_Cuenta']);
                $arrayRetiros = Retiro::BuscarRetiroParticular($_GET['Nro_Cuenta']);

                $arrayActivasDepositos = Cliente::MostrarActivas($arrayDepositos);
                $arrayActivasRetiros = Cliente::MostrarActivas($arrayRetiros);

                Deposito::MostrarArrayDepositos($arrayActivasDepositos);
                Retiro::MostrarArrayRetiro($arrayActivasRetiros);              
            }
            else
            {
                echo "Faltan Datos";
            }
            break;
        default:
            echo 'opcion invalida';
            break;
    }
}
    
?>