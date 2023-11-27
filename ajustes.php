<?php

class Ajuste
{
    public $_importe;
    public $_motivo;
    public $_numeroDeDepositoORetiro;


    public function __construct($importe,$motivo,$numeroDeDepositoORetiro)
    {
        $this->_importe = $importe;
        $this->_motivo = $motivo;
        $this->_numeroDeDepositoORetiro = $numeroDeDepositoORetiro; 
    }

    public static function ObtenerAjustes()
    {
        $arrayAjustes = array();
        $rutaArchivo = 'ajustes.json';
        
        if(file_exists($rutaArchivo))
        {
            $data = file_get_contents($rutaArchivo); 
            $arrayAsociativo = json_decode($data,true);
            foreach($arrayAsociativo as $ajuste)
            {              
                $nuevoAjuste = new Ajuste($ajuste["_importe"], $ajuste["_motivo"], $ajuste["_numeroDeDepositoORetiro"]);
                $arrayAjustes[] = $nuevoAjuste;
            }
        }   
        else 
        {
            file_put_contents($rutaArchivo, "[]");
        }
        return $arrayAjustes;
    }

    public static function GuardarAjustes($arrayAjustes)
    {
        $rutaArchivo = "ajustes.json";
        $archivoJson = json_encode($arrayAjustes,JSON_PRETTY_PRINT);
        file_put_contents($rutaArchivo,$archivoJson);
    }

    public static function AltaAjuste($nuevoAjuste)
    {
        $arrayAjustes = Ajuste::ObtenerAjustes();
        $arrayAjustes[] = $nuevoAjuste;
        Ajuste::GuardarAjustes($arrayAjustes);
    }

    
}