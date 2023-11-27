<?php
include "cliente.php";

if(isset($_POST['Nro_Cuenta']) && isset($_POST['Tipo_de_Cuenta']) && isset($_POST['Importe_a_Depositar']))
{
    
    $deposito = Cliente::DepositarMonto($_POST['Nro_Cuenta'], $_POST['Tipo_de_Cuenta'], $_POST['Importe_a_Depositar']);
    
    if($deposito!=null)
    {
        //Subir Foto
        $carpeta_archivos = 'ImagenesDeDepositos/2023/';
        $nombre_archivo = $deposito->_tipoDeCuenta . $deposito->_nroDeCuenta . $deposito->_nroDeDeposito;
        $ruta_destino = $carpeta_archivos . $nombre_archivo . ".jpg";

        if (move_uploaded_file($_FILES['archivo']['tmp_name'],  $ruta_destino))
        {
            echo "El archivo ha sido cargado correctamente.";
        }
        else
        {
            echo "Ocurrió algún error al subir el fichero. No pudo guardarse.";
        }  
    }
    
}
    
?>