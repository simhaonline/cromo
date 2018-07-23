<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteCumplido;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
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
            ->where('c.codigoCumplidoPk <> 0');
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
        $arrGuias = $query->getSingleResult();
        $arCumplido = $em->getRepository(TteCumplido::class)->find($codigoCumplido);
        $arCumplido->setCantidad(intval($arrGuias['cantidad']));
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

}