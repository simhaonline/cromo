<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenIdentificacion;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuEntidadTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuEntidadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuEntidad::class);
    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arEntidad = $em->getRepository(RhuEntidad::class)->find($codigo);
        if($arEntidad) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => 'NI', 'numeroIdentificacion' => $arEntidad->getNit()));
            if(!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($em->getReference(GenIdentificacion::class, 'NI'));
                $arTercero->setNumeroIdentificacion($arEntidad->getNit());
                $arTercero->setNombreCorto($arEntidad->getNombre());
                $arTercero->setDireccion($arEntidad->getDireccion());
                $arTercero->setTelefono($arEntidad->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }
}