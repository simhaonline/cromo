<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Utilidades\Mensajes;
use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteGuia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFactura::class);
    }

    public function lista(): array
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
     */
    public function autorizar($arFactura)
    {
        if (count($this->_em->getRepository('App:Transporte\TteGuia')->findBy(['codigoFacturaFk' => $arFactura->getCodigoFacturaPk()])) > 0) {
            $arFactura->setEstadoAutorizado(1);
            $this->_em->persist($arFactura);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede autorizar, el registro no tiene detalles');
        }
    }

    /**
     * @param $arFactura TteFactura
     */
    public function desAutorizar($arFactura)
    {
        if ($arFactura->getEstadoAutorizado() == 1 && $arFactura->getEstadoAprobado() == 0) {
            $arFactura->setEstadoAutorizado(0);
            $this->_em->persist($arFactura);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }

    /**
     * @param $arFactura TteFactura
     */
    public function aprobar($arFactura)
    {
        $arFacturaTipo = $this->_em->getRepository('App:Transporte\TteFacturaTipo')->find($arFactura->getCodigoFacturaTipoFk());
        $objFunciones = new FuncionesController();
        if ($arFactura->getEstadoAutorizado() == 1) {
            $arFactura->setEstadoAprobado(1);
            $fecha = new \DateTime('now');
            $arFactura->setFecha($fecha);
            $arFactura->setFechaVence($objFunciones->sumarDiasFecha($fecha,$arFactura->getPlazoPago()));
            $arFacturaTipo->setConsecutivo($arFacturaTipo->getConsecutivo() + 1);
            $arFactura->setNumero($arFacturaTipo->getConsecutivo());
            $this->_em->persist($arFactura);
            $this->_em->persist($arFacturaTipo);
            $this->_em->flush();
        } else {
            Mensajes::error('No se puede desautorizar, el registro ya se encuentra aprobado');
        }
    }
}