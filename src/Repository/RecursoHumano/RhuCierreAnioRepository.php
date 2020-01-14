<?php

namespace App\Repository\RecursoHumano;

use App\Controller\Estructura\FuncionesController;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuCambioSalario;
use App\Entity\RecursoHumano\RhuCierreAnio;
use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuConceptoHora;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuCosto;
use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuPago;
use App\Entity\RecursoHumano\RhuPagoDetalle;
use App\Entity\RecursoHumano\RhuCierre;
use App\Entity\RecursoHumano\RhuCierreDetalle;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\Seguridad\Usuario;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesTercero;
use App\Entity\Turno\TurSoporte;
use App\Entity\Turno\TurSoporteContrato;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PhpParser\Node\Expr\New_;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;


class RhuCierreAnioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuCierreAnio::class);
    }

    public function lista($raw)
    {

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuCierreAnio::class, 'c')
            ->select('c.codigoCierreAnioPk')
            ->addSelect('c.anio')
            ->addSelect('c.estadoAutorizado')
            ->addSelect('c.estadoAprobado')
            ->addSelect('c.estadoAnulado');
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("c.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("c.estadoAnulado = 1");
                break;
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrSeleccionados array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if (is_array($arrSeleccionados) && count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados as $codigoRegistro) {
                $arRegistro = $this->_em->getRepository(RhuCierre::class)->find($codigoRegistro);
                if ($arRegistro) {
                    $this->_em->remove($arRegistro);
                }
            }
            $this->_em->flush();
        }
    }

    /**
     * @param $arCierre RhuCierreAnio
     * @param $usuario Usuario
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arCierre, $usuario)
    {
        $em = $this->getEntityManager();
        if (!$arCierre->getEstadoAutorizado()) {
            $arCierre->setEstadoAutorizado(1);
            $em->persist($arCierre);
            $em->flush();
        }
    }


    /**
     * @param $arCierreAnio RhuCierreAnio
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobar($arCierreAnio)
    {
        $em = $this->getEntityManager();
        if ($arCierreAnio->getEstadoAutorizado() && !$arCierreAnio->getEstadoAprobado()) {
            set_time_limit(0);
            ini_set("memory_limit", -1);
            $arCierreAnio->setEstadoAprobado(1);
            $em->persist($arCierreAnio);

            $salarioMinimoNuevo = $arCierreAnio->getVrSalarioMinimo();
            $auxilioTransporteNuevo = $arCierreAnio->getVrAuxilioTransporte();

            $arConfiguracion = $em->getRepository(RhuConfiguracion::class)->find(1);
            $salarioMinimoAnterior = $arConfiguracion->getVrSalarioMinimo();

            $fechaHasta = $arCierreAnio->getAnio() . "/12/30";
            $arContratos = $em->getRepository(RhuContrato::class)->cierreAnio($fechaHasta, $salarioMinimoNuevo);
            foreach ($arContratos as $arContrato) {
                $arContratoAct = $em->getRepository(RhuContrato::class)->find($arContrato['codigoContratoPk']);
                $arCambioSalario = new RhuCambioSalario();
                $arCambioSalario->setContratoRel($arContratoAct);
                $arCambioSalario->setEmpleadoRel($arContratoAct->getEmpleadoRel());
                $arCambioSalario->setFecha(date_create($fechaHasta));
                $arCambioSalario->setVrSalarioAnterior($salarioMinimoAnterior);
                $arCambioSalario->setVrSalarioNuevo($salarioMinimoNuevo);
                $arCambioSalario->setDetalle('ACTUALIZACION SALARIO MINIMO');
                $em->persist($arCambioSalario);

                $arContratoAct->setVrSalario($salarioMinimoNuevo);
                $arContratoAct->setVrSalarioPago($salarioMinimoNuevo);
                if ($arContratoAct->getCodigoTiempoFk() == 'TMED') {
                    $arContratoAct->setVrSalarioPago($salarioMinimoNuevo / 2);
                }
                $em->persist($arContratoAct);
            }

            $arConfiguracion->setVrSalarioMinimo($salarioMinimoNuevo);
            $arConfiguracion->setVrAuxilioTransporte($auxilioTransporteNuevo);
            $em->persist($arConfiguracion);
            $em->flush();
        }
    }

    /**
     * @param $arCierre RhuCierreAnio
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function desautorizar($arCierre)
    {
        $em = $this->getEntityManager();
        if ($arCierre->getEstadoAutorizado() && !$arCierre->getEstadoAprobado()) {
            $arCierre->setEstadoAutorizado(0);
            $em->persist($arCierre);
            $em->flush();
        }
    }

}

