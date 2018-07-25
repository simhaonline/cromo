<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Transporte\TteFacturaTipo;
use App\Utilidades\Mensajes;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class TteFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFactura::class);
    }

    public function listaDql(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT f.codigoFacturaPk, 
        f.numero,
        f.fecha,
        f.vrFlete,
        f.vrManejo,
        f.vrSubtotal,
        f.vrTotal,        
        c.nombreCorto clienteNombre       
        FROM App\Entity\Transporte\TteFactura f
        LEFT JOIN f.clienteRel c'
        );
        return $query->execute();
    }
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
            ->select('f.codigoFacturaPk')
            ->addSelect('f.numero')
            ->addSelect('f.fecha')
            ->addSelect('f.vrFlete')
            ->addSelect('f.vrManejo')
            ->addSelect('f.vrSubtotal')
            ->addSelect('f.vrTotal')
            ->addSelect('f.estadoAnulado')
            ->addSelect('f.estadoAprobado')
            ->addSelect('f.estadoAutorizado')
            ->addSelect('c.nombreCorto AS clienteNombre')
            ->leftJoin('f.clienteRel', 'c')
            ->where('f.codigoFacturaPk <> 0');
        if($session->get('filtroTteFacturaNumero') != ''){
            $queryBuilder->andWhere("f.numero = {$session->get('filtroTteFacturaNumero')}");
        }
        if($session->get('filtroTteFacturaCodigoCliente')){
            $queryBuilder->andWhere("f.codigoClienteFk = {$session->get('filtroTteFacturaCodigoCliente')}");
        }
        switch ($session->get('filtroTteFacturaEstadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAprobado = 1");
                break;
        }
        switch ($session->get('filtroTteFacturaEstadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("f.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("f.estadoAnulado = 1");
                break;
        }
        $queryBuilder->orderBy('f.estadoAprobado', 'ASC');
        $queryBuilder->addOrderBy('f.estadoAprobado, f.fecha', 'DESC');
        return $queryBuilder;
    }

    public function liquidar($id): bool
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT COUNT(g.codigoGuiaPk) as cantidad, SUM(g.unidades+0) as unidades, SUM(g.pesoReal+0) as pesoReal,
            SUM(g.pesoVolumen+0) as pesoVolumen, SUM(g.vrFlete+0) as vrFlete, SUM(g.vrManejo+0) as vrManejo
        FROM App\Entity\Transporte\TteGuia g
        WHERE g.codigoFacturaFk = :codigoFactura')
            ->setParameter('codigoFactura', $id);
        $arrGuias = $query->getSingleResult();
        $vrSubtotal = intval($arrGuias['vrFlete']) + intval($arrGuias['vrManejo']);
        $arFactura = $em->getRepository(TteFactura::class)->find($id);
        $arFactura->setGuias(intval($arrGuias['cantidad']));
        $arFactura->setVrFlete(intval($arrGuias['vrFlete']));
        $arFactura->setVrManejo(intval($arrGuias['vrManejo']));
        $arFactura->setVrSubtotal($vrSubtotal);
        $arFactura->setVrTotal($vrSubtotal);
        $em->persist($arFactura);
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
                    $arGuia->setFacturaRel(null);
                    $arGuia->setEstadoFacturado(0);
                    $em->persist($arGuia);
                }
                $em->flush();
            }
        }
        return true;
    }

    /**
     * @param $arFactura TteFactura
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arFactura)
    {
        if (count($this->getEntityManager()->getRepository(TteGuia::class)->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()])) > 0) {
            $arFactura->setEstadoAutorizado(1);
            $this->getEntityManager()->persist($arFactura);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arFactura TteFactura
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desAutorizar($arFactura)
    {
        if ($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAprobado() == 0) {
            $arFactura->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arFactura);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    /**
     * @param $arFactura TteFactura
     * @throws \Doctrine\ORM\ORMException
     */
    public function aprobar($arFactura)
    {
        $arFacturaTipo = $this->getEntityManager()->getRepository(TteFacturaTipo::class)->find($arFactura->getCodigoFacturaTipoFk());
        $objFunciones = new FuncionesController();
        if ($arFactura->getEstadoAutorizado() == 1) {
            $arFactura->setEstadoAprobado(1);
            $fecha = new \DateTime('now');
            $arFactura->setFecha($fecha);
            $arFactura->setFechaVence($objFunciones->sumarDiasFecha($fecha,$arFactura->getPlazoPago()));
            $arFacturaTipo->setConsecutivo($arFacturaTipo->getConsecutivo() + 1);
            $arFactura->setNumero($arFacturaTipo->getConsecutivo());
            $this->getEntityManager()->persist($arFactura);
            $this->getEntityManager()->persist($arFacturaTipo);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }
}