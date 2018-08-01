<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Utilidades\Mensajes;

class TteCumplidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteCumplido::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCumplido::class, 'c');
        $queryBuilder
            ->select('c.codigoCumplidoPk')
            ->join('c.clienteRel', 'cl')
            ->addSelect('cl.nombreCorto')
            ->addSelect('c.fecha')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado')
            ->addSelect('c.comentario')
            ->addSelect('ct.nombre AS tipoCumplido')
            ->where('c.codigoCumplidoPk <> 0')
        ->leftJoin('c.cumplidoTipoRel', 'ct');
        $queryBuilder->orderBy('c.fecha', 'DESC');

        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("c.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
        }
        return $queryBuilder;
    }

    public function factura($codigoCliente)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCumplido::class, 'c');
        $queryBuilder
            ->select('c.codigoCumplidoPk')
            ->addSelect('c.fecha')
            ->where('c.codigoClienteFk = ' . $codigoCliente);
        $queryBuilder->orderBy('c.fecha', 'DESC');

        return $queryBuilder;
    }

    public function liquidar($codigoCumplido): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal, SUM(g.pesoVolumen+0) as pesoVolumen
        FROM App\Entity\Transporte\Guia g
        WHERE g.codigoCumplidoFk = :codigoCumplido')
            ->setParameter('codigoCumplido', $codigoCumplido);
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $em->persist($arCumplido);
        $em->flush();
        return true;
    }

    public function retirarGuia($arrGuias): bool
    {
        $em = $this->getEntityManager();
        if($arrGuias) {
            if (count($arrGuias) > 0) {
                foreach ($arrGuias AS $codigoGuia) {
                    $arGuia = $em->getRepository(TteGuia::class)->find($codigoGuia);
                    $arGuia->setCumplidoRel(null);
                    $arGuia->setEstadoCumplido(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    public function autorizar($arCumplido)
    {
            $arCumplido->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arCumplido);
            $this->getEntityManager()->flush();
    }

    public function aprobar($arCumplido)
    {
        $arCumplido->setEstadoAprobado(1);
        $this->getEntityManager()->persist($arCumplido);
        $this->getEntityManager()->flush();
    }

    public function desAutorizar($arCumplido)
    {
        if ($arCumplido->getEstadoAutorizado() == 1 && $arCumplido->getEstadoAprobado() == 0) {
            $arCumplido->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arCumplido);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

}