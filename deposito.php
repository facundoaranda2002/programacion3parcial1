<?php


class Deposito
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
    public $_nroDeDeposito;
    public $_fecha;
    public $_monto;

    public function __construct($nombre, $apellido, $tipoDocumento, $numeroDocumento, $email, $tipoDeCuenta, $saldoInicial, $nroDeCuenta, $nroDeposito = null, $fecha = null, $monto)
    {
        $this->_nombre= $nombre;
        $this->_apellido= $apellido;
        $this->_tipoDocumento= $tipoDocumento;
        $this->_numeroDocumento= $numeroDocumento;
        $this->_email= $email;
        $this->_tipoDeCuenta= $tipoDeCuenta;
        $this->_moneda= substr($tipoDeCuenta, 2);
        $this->_saldoInicial= $saldoInicial;
        $this->_nroDeCuenta= $nroDeCuenta;
        if($nroDeposito == null)
        {
            $this->_nroDeDeposito = Deposito::GenerarIdAutoIncrementalDeposito();
        }
        else
        {
            $this->_nroDeDeposito = $nroDeposito;
        }
        if($fecha == null)
        {
            $this->_fecha = date("d-m-Y");
        }
        else
        {
            $this->_fecha = $fecha;
        }
        $this->_monto = $monto;
    }
    public static function CompararNombre($deposito1, $deposito2)
    {
        return strcmp($deposito1->_nombre, $deposito2->_nombre);
    }

    public static function GenerarIdAutoIncrementalDeposito()
    {
        $nroDeDeposito = 2000;

        if(file_exists("nroDeDeposito.txt"))
        {
            $nroDeDeposito = file_get_contents("nroDeDeposito.txt");           
        }

        $nroDeDeposito++;

        file_put_contents("nroDeDeposito.txt", $nroDeDeposito);

        return $nroDeDeposito;
    }

    public static function ObtenerDepositos()
    {
        $arrayDepositos = array();
        $rutaArchivo = 'depositos.json';
        
        if(file_exists($rutaArchivo))
        {
            $data = file_get_contents($rutaArchivo); 
            $arrayAsociativo = json_decode($data,true);
            foreach($arrayAsociativo as $deposito)
            {              
                $nuevoDeposito = new Deposito($deposito["_nombre"], $deposito["_apellido"], $deposito["_tipoDocumento"], $deposito["_numeroDocumento"], $deposito["_email"], $deposito["_tipoDeCuenta"], $deposito["_saldoInicial"], $deposito["_nroDeCuenta"], $deposito["_nroDeDeposito"], $deposito["_fecha"], $deposito["_monto"]);
                $arrayDepositos[] = $nuevoDeposito;
            }
        }   
        else 
        {
            file_put_contents($rutaArchivo, "[]");
        }
        return $arrayDepositos;
    }

    public static function GuardarDepositos($arrayDepositos)
    {
        $rutaArchivo = "depositos.json";
        $archivoJson = json_encode($arrayDepositos,JSON_PRETTY_PRINT);
        file_put_contents($rutaArchivo,$archivoJson);
    }

    public static function AgregarDeposito($nuevoDeposito)
    {
        $arrayDepositos = Deposito::ObtenerDepositos();
        $arrayDepositos[] = $nuevoDeposito;
        Deposito::GuardarDepositos($arrayDepositos);
    }
    //a
    public static function TotalDepositado($TipoDeCuenta, $fecha = null)
    {
        $montoTotal = 0;
        if($fecha == null)
        {
            $fecha = New Datetime();
            $fecha->sub(new DateInterval('P1D'));
            $fecha->format('d-m-Y');
        }
        $arrayDepositos = Deposito::ObtenerDepositos();
        $arrayClientes = Cliente::ObtenerClientes(); 
        foreach ($arrayDepositos as $deposito) 
        {
            foreach($arrayClientes as $cliente)
            {
                if($cliente->_nroDeCuenta == $deposito->_nroDeCuenta && $cliente->_estaActivo)
                {
                    if($deposito->_tipoDeCuenta == $TipoDeCuenta && $deposito->_fecha == $fecha)
                    {
                        $montoTotal += $deposito->_monto;
                    }
                }
            } 
        }
        return $montoTotal;
    }
    //b
    public static function BuscarDepositoParticular($numeroDeCuenta)
    {
        $arrayDepositos = Deposito::ObtenerDepositos();
        $arrayAux = [];
        foreach ($arrayDepositos as $deposito) 
        {
            if($deposito->_nroDeCuenta == $numeroDeCuenta)
            {
                $arrayAux[] = $deposito;
            }
        }
        return $arrayAux;
    }
    //c
    public static function BuscarEntreFechas($fechaInicial, $fechaFinal)
    {
        $arrayDepositos = Deposito::ObtenerDepositos();
        $arrayAux = [];
        foreach ($arrayDepositos as $deposito) 
        {
            if($deposito->_fecha >= $fechaInicial && $deposito->_fecha <= $fechaFinal)
            {
                $arrayAux[] = $deposito;
            }
        }
        usort($arrayAux, "Deposito::CompararNombre");
        return $arrayAux;
    }
    //d
    public static function BuscarPorTipoDeCuenta($TipoDeCuenta)
    {
        $arrayDepositos = Deposito::ObtenerDepositos();
        $arrayAux = [];
        foreach ($arrayDepositos as $deposito) 
        {
            if($deposito->_tipoDeCuenta == $TipoDeCuenta)
            {
                $arrayAux[] = $deposito;
            }
        }
        return $arrayAux;
    }
    //e
    public static function BuscarPorMoneda($moneda)
    {
        $arrayDepositos = Deposito::ObtenerDepositos();
        $arrayAux = [];
        foreach ($arrayDepositos as $deposito) 
        {
            if($deposito->_moneda == $moneda)
            {
                $arrayAux[] = $deposito;
            }
        }
        return $arrayAux;
    }

    private function MostrarDeposito()
    {
        echo $this->_nombre."-";
        echo $this->_apellido."-";
        echo $this->_tipoDocumento."-";
        echo $this->_numeroDocumento."-";
        echo $this->_email."-";
        echo $this->_tipoDeCuenta."-";
        echo $this->_moneda."-";
        echo $this->_saldoInicial."-";
        echo $this->_nroDeCuenta."-";
        echo $this->_nroDeDeposito."-";
        echo $this->_fecha."-";
        echo $this->_monto;
        echo "</br>";
    }

    public static function MostrarArrayDepositos($arrayDepositos)
    {
        if($arrayDepositos != null)
        {
            echo "Nombre - Apellido - Tipo de documento - Numero de documento - Email - Tipo de cuenta - Tipo de moneda - Saldo inicial - Numero de cuenta - Numero de deposito - Fecha - Monto";
            echo "</br>";
            echo "</br>";
            foreach ($arrayDepositos as $deposito) 
            {
                $deposito->MostrarDeposito();
            }
        }
        else{
            echo "No hay lista de Depositos </br>";
        }

    }


}