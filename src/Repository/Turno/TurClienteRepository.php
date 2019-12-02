<?php

namespace App\Repository\Turno;


use App\Entity\Financiero\FinTercero;
use App\Entity\Turno\TurCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TurClienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TurCliente::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurCliente::class, 'c')
            ->select('c.codigoClientePk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.telefono')
            ->addSelect('c.movil')
            ->addSelect('c.direccion')
            ->where('c.codigoClientePk <> 0');
        if ($session->get('filtroTurClienteNombre') != '') {
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroTurClienteNombre')}%' ");
        }
        if ($session->get('filtroTurClienteNit') != '') {
            $queryBuilder->andWhere("c.numeroIdentificacion = {$session->get('filtroTurClienteIdentificacion')} ");
        }
        if ($session->get('filtroTurClienteCodigo') != '') {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTurClienteCodigo')} ");
        }
        return $queryBuilder;
    }

    public function terceroFinanciero($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arClienteInventario = $em->getRepository(TurCliente::class)->find($codigo);
        if ($arClienteInventario) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arClienteInventario->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arClienteInventario->getNumeroIdentificacion()));
            if (!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arClienteInventario->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arClienteInventario->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arClienteInventario->getNombreCorto());
                $arTercero->setDireccion($arClienteInventario->getDireccion());
                $arTercero->setTelefono($arClienteInventario->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

}
