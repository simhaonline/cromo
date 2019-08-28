<?php

namespace App\Repository\Tesoreria;

use App\Entity\Compra\ComProveedor;
use App\Entity\Financiero\FinTercero;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TesTerceroRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TesTercero::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TesTercero::class, 't')
            ->select('t.codigoTerceroPk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->where('t.codigoTerceroPk <> 0')
            ->orderBy('t.codigoTerceroPk', 'DESC');
        if ($session->get('filtroTesTerceroNombre') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroTesTerceroNombre')}%' ");
        }
        if ($session->get('filtroTesTerceroIdentificacion') != '') {
            $queryBuilder->andWhere("t.numeroIdentificacion LIKE '%{$session->get('filtroTesTerceroIdentificacion')}%' ");
        }
        if ($session->get('filtroTesTerceroCodigo') != '') {
            $queryBuilder->andWhere("t.codigoTerceroPk LIKE '%{$session->get('filtroTesTerceroCodigo')}%' ");
        }

        return $queryBuilder;
    }

    /**
     * @return mixed
     */
    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(ComProveedor::class, 'p')
            ->select('p.codigoProveedorPk')
            ->addSelect('p.numeroIdentificacion')
            ->addSelect('p.nombreCorto')
            ->addSelect('p.nombre1')
            ->addSelect('p.nombre2')
            ->addSelect('p.apellido1')
            ->addSelect('p.apellido2')
            ->addSelect('p.telefono')
            ->addSelect('p.direccion')
            ->addSelect('p.fax')
            ->addSelect('p.plazoPago')
            ->where('p.codigoProveedorPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    /**
     *
     */
    public function eliminar($arrSeleccion)
    {
        $em = $this->getEntityManager();
        try {
            foreach ($arrSeleccion as $codigoProveedor) {
                $arProveedor = $em->getRepository(ComProveedor::class)->find($codigoProveedor);
                $em->remove($arProveedor);
                $em->flush();
            }
        } catch (\Exception $ex) {
            Mensajes::error('No se puede eliminar, el registro se encuentra relacionado con algun documento');
        }

    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arCliente = $em->getRepository(TesTercero::class)->find($codigo);
        if($arCliente) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arCliente->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arCliente->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arCliente->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arCliente->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arCliente->getNombreCorto());
                $arTercero->setDireccion($arCliente->getDireccion());
                $arTercero->setTelefono($arTercero->getTelefono());
                //$arTercero->setEmail($arCliente->getCorreo());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

}