<?php

namespace App\Repository\Compra;

use App\Entity\Compra\ComProveedor;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class ComProveedorRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ComProveedor::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(ComProveedor::class, 't')
            ->select('t.codigoProveedorPk')
            ->addSelect('t.nombreCorto')
            ->addSelect('t.numeroIdentificacion')
            ->where('t.codigoProveedorPk <> 0')
            ->orderBy('t.codigoProveedorPk', 'DESC');
        if ($session->get('filtroComProveedorNombre') != '') {
            $queryBuilder->andWhere("t.nombreCorto LIKE '%{$session->get('filtroComProveedorNombre')}%' ");
        }
        if ($session->get('filtroComProveedorIdentificacion') != '') {
            $queryBuilder->andWhere("t.numeroIdentificacion LIKE '%{$session->get('filtroComProveedorIdentificacion')}%' ");
        }
        if ($session->get('filtroComProveedorCodigo') != '') {
            $queryBuilder->andWhere("t.codigoProveedorPk LIKE '%{$session->get('filtroComProveedorCodigo')}%' ");
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


}