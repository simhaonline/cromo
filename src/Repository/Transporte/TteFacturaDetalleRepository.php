<?php

namespace App\Repository\Transporte;


use App\Entity\Transporte\TteFacturaDetalle;
use App\Entity\Transporte\TteGuia;
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
        g.documentoCliente, 
        g.codigoGuiaTipoFk,
        g.nombreDestinatario,
        cd.nombre AS ciudadDestino,
        g.fechaIngreso,        
        fd.unidades,
        fd.pesoReal,
        fd.pesoVolumen,
        fd.vrFlete,
        fd.vrManejo,
        fd.vrDeclara,
        g.vrRecaudo,                   
        g.codigoOperacionIngresoFk,
        g.codigoOperacionCargoFk     
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.guiaRel g        
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE fd.codigoFacturaFk = :codigoFactura'
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
     * @param $arFactura TteFactura
     * @param $form FormInterface
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function actualizarDetalles($arrControles, $form, $arMovimiento)
    {
        $em = $this->getEntityManager();
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
        g.pesoFacturado,                
        fd.vrFlete,
        fd.vrManejo,
        g.vrDeclara,
        fd.vrFlete + fd.vrManejo AS vrTotal,
        g.nombreDestinatario,                      
        cd.nombre AS ciudadDestino
        FROM App\Entity\Transporte\TteFacturaDetalle fd 
        LEFT JOIN fd.guiaRel g      
        LEFT JOIN g.ciudadDestinoRel cd
        WHERE fd.codigoFacturaFk = :codigoFactura and fd.codigoFacturaPlanillaFk IS NULL'
        )->setParameter('codigoFactura', $codigoFactura);

        return $query->execute();

    }

}