<?php

namespace App\Repository\General;

use App\Entity\General\GenProceso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class GenProcesoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenProceso::class);
    }


    public function lista(){
        $em =   $this->getEntityManager();
        $session=new Session();
        $arProceso=$em->createQueryBuilder()
            ->from('App:General\GenProceso','genProceso')
            ->select('genProceso.codigoProcesoPk')
            ->addSelect('genProceso.nombre')
            ->addSelect('genProceso.codigoModuloFk as modulo');

        if($session->get('arSeguridadUsuarioProcesofiltroModulo')!=="" && $session->get('arSeguridadUsuarioProcesofiltroModulo')!==null){
            $modulo=$session->get('arSeguridadUsuarioProcesofiltroModulo')->getCodigoModuloPk();
            $arProceso=$arProceso->andWhere("genProceso.codigoModuloFk = '{$modulo}'");
        }

        if($session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')!=="" && $session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')!==null){
            $procesoTipo=$session->get('arSeguridadUsuarioProcesofiltroProcesoTipo')->getCodigoProcesoTipoPk();
            $arProceso=$arProceso->andWhere("genProceso.codigoProcesoTipoFk= '{$procesoTipo}'");
        }

        if($session->get('arGrupoProcesoProcesofiltroModulo')){
            $modulo=$session->get('arGrupoProcesoProcesofiltroModulo')->getCodigoModuloPk();
            $arProceso=$arProceso->andWhere("genProceso.codigoModuloFk = '{$modulo}'");
        }

        if($session->get('arGrupoProcesoProcesofiltroProcesoTipo')){
            $procesoTipo=$session->get('arGrupoProcesoProcesofiltroProcesoTipo')->getCodigoProcesoTipoPk();
            $arProceso=$arProceso->andWhere("genProceso.codigoProcesoTipoFk= '{$procesoTipo}'");
        }

        $arProceso=$arProceso->getQuery()->getResult();

        return $arProceso;
    }

}