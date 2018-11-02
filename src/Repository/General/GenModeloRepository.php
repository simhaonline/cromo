<?php

namespace App\Repository\General;

use App\Controller\Estructura\MensajesController;
use App\Entity\General\GenModelo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class GenModeloRepository extends ServiceEntityRepository
{
    private $excepcionCamposCubo = ['jsonCubo', 'sqlCubo'];

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenModelo::class);
    }

    public function lista(){
        $em =   $this->getEntityManager();
        $session=new Session();
        $arModelo=$em->createQueryBuilder()
            ->from('App:General\GenModelo','genModelo')
            ->select('genModelo.codigoModeloPk')
            ->addSelect('genModelo.codigoModuloFk as tipo');

        if($session->get('arSeguridadUsuarioModulofiltroModelo')!=="" && $session->get('arSeguridadUsuarioModulofiltroModelo')!==null){
            $arModelo=$arModelo->andWhere("genModelo.codigoModeloPk LIKE '%{$session->get('arSeguridadUsuarioModulofiltroModelo')}%'");
        }

        if($session->get('arSeguridadUsuarioModulofiltroModulo')!=="" && $session->get('arSeguridadUsuarioModulofiltroModulo')!==null){
            $modulo=$session->get('arSeguridadUsuarioModulofiltroModulo')->getCodigoModuloPk();
            $arModelo=$arModelo->andWhere("genModelo.codigoModuloFk = '{$modulo}'");
        }
        $arModelo=$arModelo->getQuery()->getResult();

        return$arModelo;
    }

}