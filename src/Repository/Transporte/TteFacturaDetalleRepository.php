<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteFacturaPlanilla;
use App\Entity\Transporte\TteGuia;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteFacturaDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaDetalle::class);
    }

    public function factura($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fd.codigoFacturaDetallePk, 
        fd.codigoFacturaPlanillaFk,
        fd.codigoGuiaFk,
        g.numero,
        g.fechaIngreso,
        g.fechaEntrega,
        g.documentoCliente, 
        g.codigoGuiaTipoFk,
        g.nombreDestinatario,
        co.nombre AS ciduadOrigen,
        cd.nombre AS ciudadDestino,
        g.fechaIngreso,        
        fd.unidades,
        fd.pesoReal,
        fd.pesoVolumen,
        fd.pesoFacturado,
        fd.vrFlete,
        fd.vrManejo,
        fd.vrDeclara,
        g.vrRecaudo,                     
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk     
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.guiaRel g        
        LEFT JOIN g.ciudadDestinoRel cd
        LEFT JOIN g.ciudadOrigenRel co
        WHERE fd.codigoFacturaFk = :codigoFactura 
        ORDER BY fd.codigoFacturaDetallePk DESC'
        )->setParameter('codigoFactura', $codigoFactura);

        return $query->execute();

    }

    public function facturaPlanilla($codigoFacturaPlanilla)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaDetalle::class, 'fd');
        $queryBuilder
            ->select('fd.codigoFacturaDetallePk')
            ->addSelect('fd.codigoGuiaFk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.codigoGuiaTipoFk')
            ->addSelect('g.fechaIngreso')
            ->addSelect('g.codigoOperacionIngresoFk')
            ->addSelect('g.codigoOperacionCargoFk')
            ->addSelect('fd.unidades')
            ->addSelect('fd.pesoReal')
            ->addSelect('fd.pesoVolumen')
            ->addSelect('fd.vrFlete')
            ->addSelect('fd.vrManejo')
            ->addSelect('cd.nombre as ciudadDestino')
            ->leftJoin('fd.guiaRel', 'g')
            ->leftJoin('g.ciudadDestinoRel', 'cd')
            ->where('fd.codigoFacturaPlanillaFk = ' . $codigoFacturaPlanilla);
        //$queryBuilder->orderBy('g.fechaIngreso', 'DESC');

        return $queryBuilder;
    }

    public function guia($codigoGuia): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fd.codigoFacturaDetallePk,
                  fd.codigoFacturaFk,
                  f.numero,
                  f.fecha,                  
                  ft.nombre AS tipoFactura,
                  fd.codigoFacturaPlanillaFk,
                  fd.vrFlete,
                  fd.vrManejo             
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.facturaRel f
        LEFT JOIN f.facturaTipoRel ft
        WHERE fd.codigoGuiaFk = :codigoGuia ORDER BY f.fecha ASC'
        )->setParameter('codigoGuia', $codigoGuia);

        return $query->execute();
    }

    /**
     * @param $arrControles array
     * @param $form
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arMovimiento)
    {
        $em = $this->getEntityManager();
        if(isset($arrControles['arrCodigo'])) {
            $arrDocumentoCliente = $arrControles['arrDocumentoCliente'];
            $arrDeclara = $arrControles['arrDeclara'];
            $arrFlete = $arrControles['arrFlete'];
            $arrManejo = $arrControles['arrManejo'];
            $arrCodigo = $arrControles['arrCodigo'];
            foreach ($arrCodigo as $codigoFacturaDetalle) {
                $arFacturaDetalle = $em->getRepository(TteFacturaDetalle::class)->find($codigoFacturaDetalle);
                $arFacturaDetalle->setVrDeclara($arrDeclara[$codigoFacturaDetalle]);
                $arFacturaDetalle->setVrFlete($arrFlete[$codigoFacturaDetalle]);
                $arFacturaDetalle->setVrManejo($arrManejo[$codigoFacturaDetalle]);
                $em->persist($arFacturaDetalle);
                $arGuia = $em->getRepository(TteGuia::class)->find($arFacturaDetalle->getCodigoGuiaFk());
                $arGuia->setDocumentoCliente($arrDocumentoCliente[$codigoFacturaDetalle]);
                $arGuia->setVrDeclara($arrDeclara[$codigoFacturaDetalle]);
                $arGuia->setVrFlete($arrFlete[$codigoFacturaDetalle]);
                $arGuia->setVrManejo($arrManejo[$codigoFacturaDetalle]);
                $em->persist($arGuia);
            }
            $em->flush();
        }
    }

    public function formatoFactura($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT fd.codigoGuiaFk, 
        g.numero,
        g.documentoCliente, 
        g.fechaIngreso,        
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk,     
        fd.unidades,
        p.nombre AS producto,
        g.pesoReal,
        ep.nombre AS empaque,
        g.pesoFacturado,                
        fd.vrFlete,
        fd.vrManejo,
        g.vrDeclara,
        fd.vrFlete + fd.vrManejo AS vrTotal,
        g.nombreDestinatario,                      
        cd.nombre AS ciudadDestino,
        co.nombre AS ciudadOrigen
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.guiaRel g      
        LEFT JOIN g.ciudadDestinoRel cd
        LEFT JOIN g.ciudadOrigenRel co
        LEFT JOIN g.productoRel p
        LEFT JOIN g.empaqueRel ep
        WHERE fd.codigoFacturaFk = :codigoFactura and fd.codigoFacturaPlanillaFk IS NULL ORDER BY fd.codigoGuiaFk ASC'
        )->setParameter('codigoFactura', $codigoFactura);
        return $query->execute();

    }

    public function formatoFacturaDestino($codigoFactura): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT 
        cd.nombre AS ciudadDestino,
        count(fd.codigoFacturaDetallePk) as guias,
        sum(fd.unidades) as unidades,     
        sum(fd.vrFlete) as vrFlete,
        sum(fd.vrManejo) as vrManejo,
        sum(g.pesoFacturado) as pesoFacturado,
        sum(fd.vrDeclara) as vrDeclara
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.guiaRel g 
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE fd.codigoFacturaFk = :codigoFactura and fd.codigoFacturaPlanillaFk IS NULL GROUP BY g.codigoCiudadDestinoFk ORDER BY g.codigoCiudadDestinoFk ASC'
        )->setParameter('codigoFactura', $codigoFactura);
        return $query->execute();

    }

    public function detalle()
    {

        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaDetalle::class, 'fd')
            ->select('fd.codigoFacturaDetallePk')
            ->addSelect('ft.nombre AS facturaTipo')
            ->addSelect('fd.codigoFacturaFk')
            ->addSelect('f.numero AS numeroFactura')
            ->addSelect('f.fecha AS fechaFactura')
            ->addSelect('g.fechaEntrega')
            ->addSelect('c.nombreCorto AS clienteNombreCorto')
            ->addSelect('fd.codigoGuiaFk')
            ->addSelect('g.numero')
            ->addSelect('g.documentoCliente')
            ->addSelect('g.nombreDestinatario')
            ->addSelect('cd.nombre AS ciudadDestino')
            ->addSelect('g.vrFlete + g.vrManejo AS total')
            ->addSelect('fd.unidades')
            ->addSelect('fd.pesoReal')
            ->addSelect('fd.pesoVolumen')
            ->addSelect('g.vrRecaudo')
            ->addSelect('fd.vrDeclara')
            ->addSelect('fd.vrFlete')
            ->addSelect('fd.vrManejo')
            ->leftJoin('fd.facturaRel', 'f')
            ->leftJoin('f.facturaTipoRel', 'ft')
            ->leftJoin('f.clienteRel', 'c')
            ->leftJoin('fd.guiaRel', 'g')
            ->leftJoin('g.ciudadDestinoRel', 'cd');
        $fecha = new \DateTime('now');
        if ($session->get('filtroTteCodigoCliente')) {
            $queryBuilder->andWhere("c.codigoClientePk = {$session->get('filtroTteCodigoCliente')}");
        }
        if ($session->get('filtroNumeroFactura')) {
            $queryBuilder->andWhere("f.numero = {$session->get('filtroNumeroFactura')}");
        }
        if ($session->get('filtroFecha') == true) {
            if ($session->get('filtroFechaDesde') != null) {
                $queryBuilder->andWhere("f.fecha >= '{$session->get('filtroFechaDesde')} 00:00:00'");
            } else {
                $queryBuilder->andWhere("f.fecha >='" . $fecha->format('Y-m-d') . " 00:00:00'");
            }
            if ($session->get('filtroFechaHasta') != null) {
                $queryBuilder->andWhere("f.fecha <= '{$session->get('filtroFechaHasta')} 23:59:59'");
            } else {
                $queryBuilder->andWhere("f.fecha <= '" . $fecha->format('Y-m-d') . " 23:59:59'");
            }
        }
        $queryBuilder->orderBy('f.fecha', 'DESC');

        return $queryBuilder;

    }

    public function guiaPrecio($codigoGuia)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaDetalle::class, 'fd');
        $queryBuilder
            ->select('SUM(fd.vrFlete)+0 AS vrFlete')
            ->where("fd.codigoGuiaFk = " . $codigoGuia);
        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * @param $arrPlanillas
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function retirarPlanilla($arrPlanillas)
    {
        $em = $this->_em;
        if ($arrPlanillas) {
            $arrRespuesta = [];
            foreach ($arrPlanillas as $codigoPlanilla) {
                $arPlanilla = $em->find(TteFacturaPlanilla::class, $codigoPlanilla);
                $arFacturaDetalles = $em->getRepository(TteFacturaDetalle::class)->contarDetallesPlanilla($codigoPlanilla);
                if ($arFacturaDetalles[1] > 0) {
                    $arrRespuesta[] = $arPlanilla->getCodigoFacturaPlanillaPk();
                } else {
                    $em->remove($arPlanilla);
                }
            }
            if (count($arrRespuesta) == 0) {
                $em->flush();
            } else {
                Mensajes::error('Las siguientes planillas no se pueden eliminar, tienen registros asociados: ' . implode(',', $arrRespuesta));
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function contarDetallesPlanilla($id)
    {
        return $this->_em->createQueryBuilder()
            ->from(TteFacturaDetalle::class, 'fd')
            ->select('COUNT(fd.codigoFacturaDetallePk)')
            ->where('fd.codigoFacturaPlanillaFk = ' . $id)->getQuery()->getOneOrNullResult();

    }

    public function contabilizarIngreso($codigoFactura)
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteFacturaDetalle::class, 'fd')
            ->select('g.codigoOperacionIngresoFk')
            ->addSelect('SUM(fd.vrFlete) as vrFlete')
            ->addSelect('SUM(fd.vrManejo) as vrManejo')
            ->leftJoin('fd.guiaRel', 'g')
            ->groupBy('g.codigoOperacionIngresoFk')
            ->where('fd.codigoFacturaFk=' . $codigoFactura);
        $arFacturaDetalle = $queryBuilder->getQuery()->getResult();
        return $arFacturaDetalle;
    }
}
