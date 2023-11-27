<?php

/*
nombre
apellido
tipoDocumento
numeroDocumento
email
tipoDeCuenta
moneda
saldoInicial
*/

include "deposito.php";
include "retiro.php";
include "ajustes.php";

class Cliente
{
    public $_nombre;
    public $_apellido;
    public $_tipoDocumento;
    public $_numeroDocumento;
    public $_email;
    public $_tipoDeCuenta;
    public $_moneda;
    public $_saldoInicial;
    public $_nroDeCuenta;
    public $_estaActivo;
    public function __construct($nombre, $apellido, $tipoDocumento, $numeroDocumento, $email, $tipoDeCuenta, $saldoInicial = 0, $nroDeCuenta = null, $estaActivo = true)
    {
        $this->_nombre= $nombre;
        $this->_apellido= $apellido;
        $this->_tipoDocumento= $tipoDocumento;
        $this->_numeroDocumento= $numeroDocumento;
        $this->_email= $email;
        $this->_tipoDeCuenta= $tipoDeCuenta;
        $this->_moneda= substr($tipoDeCuenta, 2);
        $this->_saldoInicial= $saldoInicial;
        if($nroDeCuenta == null)
        {
            $this->_nroDeCuenta = Cliente::GenerarIdAutoIncrementalCuenta();
        }
        else
        {
            $this->_nroDeCuenta = $nroDeCuenta;
        }
        $this->_estaActivo= $estaActivo;
    }

    public static function GenerarIdAutoIncrementalCuenta()
    {
        $nroDeCuenta = 100000;

        if(file_exists("nroDeCuenta.txt"))
        {
            $nroDeCuenta = file_get_contents("nroDeCuenta.txt");           
        }

        $nroDeCuenta++;

        file_put_contents("nroDeCuenta.txt", $nroDeCuenta);

        return $nroDeCuenta;
    }

    public static function ObtenerClientes()
    {
        $arrayClientes = array();
        $rutaArchivo = 'banco.json';
        
        if(file_exists($rutaArchivo))
        {
            $data = file_get_contents($rutaArchivo); 
            $arrayAsociativo = json_decode($data,true);
            foreach($arrayAsociativo as $cliente)
            {              
                $nuevoCliente = new Cliente($cliente["_nombre"], $cliente["_apellido"], $cliente["_tipoDocumento"], $cliente["_numeroDocumento"], $cliente["_email"], $cliente["_tipoDeCuenta"], $cliente["_saldoInicial"], $cliente["_nroDeCuenta"],$cliente["_estaActivo"]);
                $arrayClientes[] = $nuevoCliente;
            }
        }   
        else 
        {
            file_put_contents($rutaArchivo, "[]");
        }
        return $arrayClientes;
    }

    public static function GuardarClientes($arrayClientes)
    {
        $rutaArchivo = "banco.json";
        $archivoJson = json_encode($arrayClientes,JSON_PRETTY_PRINT);
        file_put_contents($rutaArchivo,$archivoJson);
    }

    public static function AgregarCliente($nuevoCliente)
    {
        $arrayClientes = Cliente::ObtenerClientes();
        $usuarioExiste = false;
        foreach ($arrayClientes as $cliente) 
        {
           if(Cliente::ValidarClienteNombre($nuevoCliente, $cliente))
           {
                $cliente->_saldoInicial += $nuevoCliente->_saldoInicial;
                $usuarioExiste = true;

                $nroDeCuenta = file_get_contents("nroDeCuenta.txt");           
                $nroDeCuenta--;
                file_put_contents("nroDeCuenta.txt", $nroDeCuenta);
                break;
           }
        }
        if(!$usuarioExiste)
        {
            $arrayClientes[] = $nuevoCliente;
        }
        Cliente::GuardarClientes($arrayClientes);
        return $usuarioExiste;
    }

    public static function ValidarClienteNombre($cliente1 , $cliente2)
    {
        $exito = false;
        if($cliente1->_nombre == $cliente2->_nombre && $cliente1->_apellido == $cliente2->_apellido && $cliente1->_tipoDeCuenta == $cliente2->_tipoDeCuenta)
        {
            $exito = true;
        }
        return $exito;
    }
    public static function ConsultarCliente($numeroDeCuenta, $tipoDeCuenta)
    {
        $arrayClientes = Cliente::ObtenerClientes();
        
        $retorno = Cliente::ValidarClienteCuenta($arrayClientes, $numeroDeCuenta, $tipoDeCuenta);

        if(is_string($retorno))
        {
            echo $retorno;
        }
        else
        {
            echo $arrayClientes[$retorno]->_moneda . $arrayClientes[$retorno]->_saldoInicial;
        }
    }

    public static function ValidarClienteCuenta($arrayClientes, $numeroDeCuenta, $tipoDeCuenta)
    {
        $retorno = "no existe la combinación de nro y tipo de cuenta";
        $contador = 0;
        foreach ($arrayClientes as $cliente) 
        {
            if($cliente->_nroDeCuenta == $numeroDeCuenta)
            {
                if($cliente->_tipoDeCuenta == $tipoDeCuenta)
                {
                    $retorno = $contador;
                }
                else
                {
                    $retorno = "tipo de cuenta incorrecto";
                }
                break;
            }
            $contador++;
        }
        return $retorno;
    }

    public static function DepositarMonto($numeroDeCuenta, $tipoDeCuenta, $importe)
    {
        $retorno = null; 

        $arrayClientes = Cliente::ObtenerClientes();
        
        $validacion = Cliente::ValidarClienteCuenta($arrayClientes, $numeroDeCuenta, $tipoDeCuenta);
        
        if(is_string($validacion))
        {
            echo $validacion;
        }
        else
        {
            $cliente = $arrayClientes[$validacion];
            $cliente->_saldoInicial += $importe;
            Cliente::GuardarClientes($arrayClientes);
            
            $deposito = new Deposito($cliente->_nombre, $cliente->_apellido, $cliente->_tipoDocumento, $cliente->_numeroDocumento, $cliente->_email, $cliente->_tipoDeCuenta, $cliente->_saldoInicial, $cliente->_nroDeCuenta, null, null, $importe);
            Deposito::AgregarDeposito($deposito);
            $retorno = $deposito;
        }

        return $retorno;
    }

    public static function ModificarCuenta($cliente)
    {
        $arrayClientes = Cliente::ObtenerClientes();

        $validacion = Cliente::ValidarClienteCuenta($arrayClientes, $cliente->_nroDeCuenta, $cliente->_tipoDeCuenta);

        if(is_string($validacion))
        {
            echo "No existe la cuenta";
        }
        else
        {
            $arrayClientes[$validacion]->_nombre = $cliente->_nombre;
            $arrayClientes[$validacion]->_apellido = $cliente->_apellido;
            $arrayClientes[$validacion]->_tipoDocumento = $cliente->_tipoDocumento;
            $arrayClientes[$validacion]->_numeroDocumento = $cliente->_numeroDocumento;
            $arrayClientes[$validacion]->_email = $cliente->_email;
            // $arrayClientes[$validacion]->_tipoDeCuenta = $cliente->_tipoDeCuenta;
            //$arrayClientes[$validacion]->_moneda = $cliente->_moneda;
            // $arrayClientes[$validacion]->_nroDeCuenta = $cliente->_nroDeCuenta;
            Cliente::GuardarClientes($arrayClientes);
            echo "Cuenta modificada correctamente";
        }
        
    }

    public static function RetirarMonto($numeroDeCuenta, $tipoDeCuenta, $importe)
    {
        $arrayClientes = Cliente::ObtenerClientes();
        $validacion = Cliente::ValidarClienteCuenta($arrayClientes, $numeroDeCuenta, $tipoDeCuenta);
        if(is_string($validacion))
        {
            echo $validacion;
        }
        else
        {

            $cliente = $arrayClientes[$validacion];  
            if($cliente->_saldoInicial >= $importe)
            {
               // BancoJson
                $cliente->_saldoInicial -= $importe;
                Cliente::GuardarClientes($arrayClientes);
                // RetiroJson   
                $retiro = new Retiro($cliente->_nombre, $cliente->_apellido, $cliente->_tipoDocumento, $cliente->_numeroDocumento, $cliente->_email, $cliente->_tipoDeCuenta, $cliente->_saldoInicial, $cliente->_nroDeCuenta, null, null, $importe);
                Retiro::AltaRetirar($retiro);
                echo "Retiro correctamente";
            }
            else
            {
                echo "Saldo inferior al monto a retirar";
            }
             
        }
    }

    public static function AjusteCuenta($importe,$motivo,$numeroDeDepositoORetiro)
    {
        $existe = Cliente::ValidarNroDepositoORetiro($numeroDeDepositoORetiro,$motivo);
        if($existe != null && $importe >= 0)
        {
            $montoAModificar = $importe - $existe->_monto;
            $arrayClientes = Cliente::ObtenerClientes();
            foreach ($arrayClientes as $clientes) 
            {
                if($clientes->_nroDeCuenta == $existe->_nroDeCuenta)
                {
                    if($motivo== "Deposito")
                    {
                        $clientes->_saldoInicial += $montoAModificar;
                        echo "Deposito ajustado";
                    }
                    else
                    {
                        $clientes->_saldoInicial -= $montoAModificar;
                        echo "Retiro ajustado";
                    }
                    break;
                }
            }
            Cliente::GuardarClientes($arrayClientes);
            $ajuste = new Ajuste($importe,$motivo,$numeroDeDepositoORetiro);
            Ajuste::AltaAjuste($ajuste); 
        }
        else
        {
            echo "No existe el id o el motivo es incorrecto";
        }

    }

    public static function ValidarNroDepositoORetiro($numeroDeDepositoORetiro,$motivo)
    {
        $arrayAux = [];
        $existe = null;
        if($motivo == "Deposito")
        {
            $arrayAux = Deposito::ObtenerDepositos();
            foreach($arrayAux as $deposito)
            {
                if($deposito->_nroDeDeposito == $numeroDeDepositoORetiro)
                {
                    $existe = $deposito;
                    break;
                }   
            }

        }
        else if($motivo == "Retiro")
        {
            $arrayAux =Retiro::ObtenerRetiros();
            foreach($arrayAux as $retiro)
            {
                if($retiro->_nroDeRetiro == $numeroDeDepositoORetiro)
                {
                    $existe = $retiro;
                    break;
                }   
            }
        }
        return $existe;
    }

    public static function BorrarCliente($numeroDeCuenta, $tipoDeCuenta)
    {
        $arrayClientes = Cliente::ObtenerClientes();
        foreach ($arrayClientes as $clientes) 
        {
            if($clientes->_nroDeCuenta == $numeroDeCuenta && $clientes->_tipoDeCuenta == $tipoDeCuenta)
            {
                $clientes->_estaActivo = false;


                $nombre_archivo = $clientes->_nroDeCuenta . $clientes->_tipoDeCuenta;

                $ruta_origen = 'ImagenesDeCuentas/2023/' . $nombre_archivo . ".jpg";

                $ruta_destino = 'ImagenesBackupCuentas/2023/'.$nombre_archivo.".jpg";

                if (rename($ruta_origen , $ruta_destino)) {
                    echo "Cuenta borrada correctamente";
                }
                break;
            }
        }
        Cliente::GuardarClientes($arrayClientes);
    }

    public static function MostrarActivas($arrayRetiros)
    {
        $arrayClientes = Cliente::ObtenerClientes();
        $arrayAux = [];
        foreach ($arrayRetiros as $retiro) 
        {
            foreach($arrayClientes as $cliente)
            {   
                if($retiro->_nroDeCuenta == $cliente->_nroDeCuenta && $cliente->_estaActivo == true)
                {
                    $arrayAux[] = $retiro;
                }
            }

        }
        return $arrayAux;
    }

    // public static function MostrarMontoRetiro($arrayRetiros)
    // {
    //     $arrayClientes = Cliente::ObtenerClientes();
    //     $montoTotal = 0;
    //     foreach ($arrayRetiros as $retiro) 
    //     {
    //         foreach($arrayClientes as $cliente)
    //         {   
    //             if($retiro->_nroDeCuenta == $cliente->_nroDeCuenta && $cliente->_estaActivo == true)
    //             {
    //                 $montoTotal += $retiro->_monto;
    //             }
    //         }

    //     }
    //     return $montoTotal;
    // }

}
?>