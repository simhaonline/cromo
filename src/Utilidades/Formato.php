<?php


namespace App\Utilidades;

use App\Formato\General\GenerarFormatoCarta;
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
}