<?php

namespace App\Repository\Transporte;

use App\Entity\Financiero\FinTercero;
use App\Entity\Tesoreria\TesTercero;
use App\Entity\Transporte\TtePoseedor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use SoapClient;
use Symfony\Component\HttpFoundation\Session\Session;

class TtePoseedorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TtePoseedor::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TtePoseedor::class, 'p')
            ->select('p.codigoPoseedorPk')
            ->addSelect('p.codigoIdentificacionFk')
            ->addSelect('p.numeroIdentificacion')
            ->addSelect('p.nombreCorto')
            ->addSelect('p.nombre1')
            ->addSelect('p.nombre2')
            ->addSelect('p.apellido1')
            ->addSelect('p.apellido2')
            ->addSelect('p.direccion')
            ->addSelect('p.codigoCiudadFk')
            ->addSelect('p.telefono')
            ->addSelect('p.movil')
            ->addSelect('p.correo');

        if ($session->get('TtePoseedor_codigoPoseedorPk')) {
            $queryBuilder->andWhere("p.codigoPoseedorPk = '{$session->get('TtePoseedor_codigoPoseedorPk')}'");
        }

        if ($session->get('TtePoseedor_nombreCorto')) {
            $queryBuilder->andWhere("p.nombreCorto LIKE '%{$session->get('TtePoseedor_nombreCorto')}%' ");

        }

        return $queryBuilder;
    }
    
    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TtePoseedor','p')
            ->select('p.codigoPoseedorPk AS ID')
            ->addSelect('p.nombreCorto AS NOMBRE')
            ->addSelect('p.numeroIdentificacion AS IDENTIFICACION');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }
    
    public function dqlRndc($codigoPoseedor, $codigoPropietario): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        p.codigoIdentificacionFk,
        p.numeroIdentificacion,
        p.nombre1,
        p.apellido1,
        p.apellido2,
        p.telefono,
        p.movil,
        p.direccion, 
        c.codigoInterface AS codigoCiudad,
        i.codigoInterface AS tipoIdentificacion
        FROM App\Entity\Transporte\TtePoseedor p
        LEFT JOIN p.identificacionRel i         
        LEFT JOIN p.ciudadRel c     
        WHERE p.codigoPoseedorPk = :codigoPoseedor OR p.codigoPoseedorPk = :codigoPropietario'
        )->setParameter('codigoPoseedor', $codigoPoseedor)
            ->setParameter('codigoPropietario', $codigoPropietario);
        $arTercerosPoseedores =  $query->getResult();
        return $arTercerosPoseedores;

    }

    public function dqlRndcManifiesto($codigoPoseedor): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        p.codigoIdentificacionFk,
        p.numeroIdentificacion,
        pi.codigoInterface AS tipoIdentificacion
        FROM App\Entity\Transporte\TtePoseedor p   
        LEFT JOIN p.identificacionRel pi           
        WHERE p.codigoPoseedorPk = :codigoPoseedor'
        )->setParameter('codigoPoseedor', $codigoPoseedor);
        $arPoseedor =  $query->getSingleResult();
        return $arPoseedor;

    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arPoseedor = $em->getRepository(TtePoseedor::class)->find($codigo);
        if($arPoseedor) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arPoseedor->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arPoseedor->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arPoseedor->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arPoseedor->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arPoseedor->getNombreCorto());
                $arTercero->setDireccion($arPoseedor->getDireccion());
                $arTercero->setTelefono($arPoseedor->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

    public function terceroTesoreria($arPoseedor)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        if($arPoseedor) {
            $arTercero = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arPoseedor->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arPoseedor->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new TesTercero();
                $arTercero->setIdentificacionRel($arPoseedor->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arPoseedor->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arPoseedor->getNombreCorto());
                $arTercero->setDireccion($arPoseedor->getDireccion());
                $arTercero->setTelefono($arPoseedor->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

}