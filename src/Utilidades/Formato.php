<?php


namespace App\Utilidades;

use App\Formato\General\GenerarFormatoCarta;
use App\Formato\RecursoHumano\Contrato;
use Doctrine\ORM\EntityManagerInterface;


class Formato
{
    private $em;

    /*
    */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function generarFormatoCarta($codigoFormato, $parametros)
    {
        $formatoCarta = new GenerarFormatoCarta();
        $formatoCarta->Generar($this->em, $codigoFormato, $parametros);
    }
    public function generarFormatoContrato($codigoFormato, $parametros,$id)
    {
        $formatoContrato = new Contrato();
        $formatoContrato->Generar($this->em, $codigoFormato, $parametros, $id);
    }
}