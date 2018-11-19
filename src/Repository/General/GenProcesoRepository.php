<?php

namespace App\Repository\General;

use App\Entity\General\GenProceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GenProcesoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenProceso::class);
    }


    public function lista(){
        $em =   $this->getEntityManager();
        $session=new Session();
        $arProceso=$em->createQueryBuilder()
            ->from('App:General\GenProceso','genProceso')
            ->join('genProceso.procesoTipoRel','pt')
            ->select('genProceso.codigoProcesoPk')
            ->addSelect('genProceso.nombre')
            ->addSelect('pt.nombre as tipo')
            ->addSelect('genProceso.codigoModuloFk as modulo');

        if($session->get('arSeguridadUsuarioProcesofiltroModulo')!=="" && $session->get('arSeguridadUsuarioProcesofiltroModulo')!==null){
            $modulo=$session->get('arSeguridadUsuarioProcesofiltroModulo')->getCodigoModuloPk();
            $arProceso=$arProceso->andWhere("genProceso.codigoModuloFk = '{$modulo}'");
        }

        if($session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')!=="" && $session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')!==null){
            $procesoTipo=$session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')->getCodigoProcesoTipoPk();
            $arProceso=$arProceso->andWhere("pt.codigoProcesoTipoPk= '{$procesoTipo}'");
        }
        $arProceso=$arProceso->getQuery()->getResult();

        return $arProceso;
    }

}