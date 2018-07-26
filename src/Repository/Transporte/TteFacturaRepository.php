<?php

namespace App\Repository\Transporte;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Cartera\CarCliente;
use App\Entity\Cartera\CarCuentaCobrarTipo;
use App\Entity\Transporte\TteFacturaDetalle;
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
            ->addSelect('f.guias')
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
        if($session->get('filtroTteCodigoCliente')){
            $queryBuilder->andWhere("f.codigoClienteFk = {$session->get('filtroTteCodigoCliente')}");
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
            'SELECT COUNT(fd.codigoFacturaDetallePk) as cantidad, SUM(fd.unidades+0) as unidades, SUM(fd.pesoReal+0) as pesoReal,
            SUM(fd.pesoVolumen+0) as pesoVolumen, SUM(fd.vrFlete+0) as vrFlete, SUM(fd.vrManejo+0) as vrManejo
        FROM App\Entity\Transporte\TteFacturaDetalle fd
        WHERE fd.codigoFacturaFk = :codigoFactura')
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


    public function retirarDetalle($arrDetalles): bool
    {
        $em = $this->getEntityManager();
        if ($arrDetalles) {
            if (count($arrDetalles) > 0) {
                foreach ($arrDetalles AS $codigo) {
                    $arFacturaDetalle = $em->getRepository(TteFacturaDetalle::class)->find($codigo);
                    $arGuia = $em->getRepository(TteGuia::class)->find($arFacturaDetalle->getCodigoGuiaFk());
                    $arGuia->setFacturaRel(NULL);
                    $arGuia->setEstadoFacturaGenerada(0);
                    $em->persist($arGuia);
                    $em->remove($arFacturaDetalle);
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


    public function aprobar($arFactura): string
    {
        $respuesta = "";
        $em = $this->getEntityManager();
        $objFunciones = new FuncionesController();
        if (!$arFactura->getEstadoAprobado()) {
            if ($arFactura->getGuias() > 0) {
                $fechaActual = new \DateTime('now');
                $query = $em->createQuery('UPDATE App\Entity\Transporte\TteGuia g set g.estadoFacturado = 1, g.fechaFactura=:fecha 
                      WHERE g.codigoFacturaFk = :codigoFactura')
                    ->setParameter('codigoFactura', $arFactura->getCodigoFacturaPk())
                    ->setParameter('fecha', $fechaActual->format('Y-m-d H:i'));
                $query->execute();

                $arFactura->setEstadoAprobado(1);
                $fecha = new \DateTime('now');
                $arFactura->setFecha($fecha);
                $arFactura->setFechaVence($objFunciones->sumarDiasFecha($fecha,$arFactura->getPlazoPago()));
                $arFacturaTipo = $this->getEntityManager()->getRepository(TteFacturaTipo::class)->find($arFactura->getCodigoFacturaTipoFk());
                $arFacturaTipo->setConsecutivo($arFacturaTipo->getConsecutivo() + 1);
                $arFactura->setNumero($arFacturaTipo->getConsecutivo());
                $this->getEntityManager()->persist($arFactura);
                $this->getEntityManager()->persist($arFacturaTipo);

                $arCuentaCobrarTipo = $em->getRepository(CarCuentaCobrarTipo::class)->find($arFactura->getFacturaTipoRel()->getCodigoCuentaCobrarFk());
                $arClienteCartera = $em->getRepository(CarCliente::class)->findOneBy(['nit' => $arFactura->getClienteRel()->getNit()]);

                if (!$arClienteCartera) {
                    $arClienteCartera = new CarCliente();
                    $arClienteCartera->setFormaPagoRel($arMovimiento->getTerceroRel()->getFormaPagoRel());
                    $arClienteCartera->setCiudadRel($arMovimiento->getTerceroRel()->getCiudadRel());
                    $arClienteCartera->setNit($arMovimiento->getTerceroRel()->getNumeroIdentificacion());
                    $arClienteCartera->setDigitoVerificacion($arMovimiento->getTerceroRel()->getDigitoVerificacion());
                    $arClienteCartera->setNombreCorto($arMovimiento->getTerceroRel()->getNombreCorto());
                    $arClienteCartera->setPlazoPago($arMovimiento->getTerceroRel()->getPlazoPagoCliente());
                    $arClienteCartera->setDireccion($arMovimiento->getTerceroRel()->getDireccion());
                    $arClienteCartera->setTelefono($arMovimiento->getTerceroRel()->getTelefono());
                    $arClienteCartera->setCelular($arMovimiento->getTerceroRel()->getCelular());
                    $arClienteCartera->setFax($arMovimiento->getTerceroRel()->getFax());
                    $arClienteCartera->setEmail($arMovimiento->getTerceroRel()->getEmail());
                    //$arClienteCartera->setCodigoUsuario($arUsuario->getUserName());
                    $em->persist($arClienteCartera);
                }
                /*if ($arMovimiento->getDocumentoRel()->getGeneraCartera()) {
                    $arCuentaCobrar = new \Brasa\CarteraBundle\Entity\CarCuentaCobrar();
                    $arCuentaCobrar->setClienteRel($arClienteCartera);
                    $arCuentaCobrar->setFacturaInventarioRel($arMovimiento);
                    //$arCuentaCobrar->setAsesorRel($arAsesor);
                    $arCuentaCobrar->setCuentaCobrarTipoRel($arCuentaCobrarTipo);
                    $arCuentaCobrar->setFecha($arMovimiento->getFecha());
                    $arCuentaCobrar->setFechaVence($arMovimiento->getFecha());
                    $arCuentaCobrar->setNumeroDocumento($arMovimiento->getNumero());
                    $arCuentaCobrar->setCodigoFactura($arMovimiento->getCodigoMovimientoPk());
                    $arCuentaCobrar->setSoporte($arMovimiento->getSoporte());
                    $arCuentaCobrar->setValorOriginal($arMovimiento->getVrNeto());
                    $arCuentaCobrar->setSaldo($arMovimiento->getVrNeto());
                    $arCuentaCobrar->setPlazo($arClienteCartera->getPlazoPago());
                    $arCuentaCobrar->setOperacion($arMovimiento->getOperacionComercial());
                    $arCuentaCobrar->setSubtotal($arMovimiento->getVrSubtotal());
                    $arCuentaCobrar->setAbono(0);
                    $em->persist($arCuentaCobrar);
                }*/



                $em->flush();
            } else {
                $respuesta = "La factura debe tener guias asignadas";
            }
        } else {
            $respuesta = "La factura no puede estar previamente aprobada";
        }

        return $respuesta;
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TteFactura::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TteFacturaDetalle::class)->findBy(['codigoFacturaFk' => $arRegistro->getCodigoFacturaPk()])) <= 0) {
                                $this->getEntityManager()->remove($arRegistro);
                            } else {
                                $respuesta = 'No se puede eliminar, el registro tiene detalles';
                            }
                        } else {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                }
                if($respuesta != ''){
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

}