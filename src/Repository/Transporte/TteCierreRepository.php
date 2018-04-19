<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCierre;
use App\Entity\Transporte\TteDespachoDetalle;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteCierreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCierre::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT c.codigoCierrePk, 
        c.anio, 
        c.mes,
        c.estadoGenerado
        FROM App\Entity\Transporte\TteCierre c                 
        ORDER BY c.anio, c.mes DESC '
        );
        return $query->execute();
    }

    public function generar($codigoCierre): string
    {
        $em = $this->getEntityManager();
        $respuesta = "";
        $arCierre = $em->getRepository(TteCierre::class)->find($codigoCierre);
        $arGuias = $em->getRepository(TteGuia::class)->periodoCierre($arCierre->getAnio(), $arCierre->getMes());
        foreach ($arGuias as $arGuia) {
            $arDespachoDetalle = $em->getRepository(TteDespachoDetalle::class)->guiaCosto($arGuia['codigoGuiaPk']);
            if($arDespachoDetalle) {

            }

        }

        return $respuesta;
    }

}