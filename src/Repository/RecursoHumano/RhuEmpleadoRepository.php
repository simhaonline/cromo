<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Tesoreria\TesTercero;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuEmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuEmpleado::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RHuEmpleado', 'e')
            ->select('e.codigoEmpleadoPk AS ID');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->where('e.codigoEmpleadoPk <> 0');
        if ($session->get('filtroRhuEmpleadoCodigo')) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroRhuEmpleadoCodigo')}");
        }
        if ($session->get('filtroRhuEmpleadoNombre')) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroRhuEmpleadoNombre')}%'");
        }
        if ($session->get('filtroRhuEmpleadoIdentificacion')) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroRhuEmpleadoIdentificacion')}' ");
        }
        switch ($session->get('filtroRhuEmpleadoEstadoContrato')) {
            case '0':
                $queryBuilder->andWhere("e.estadoContrato = 1");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoContrato = 0");
                break;
        }
        return $queryBuilder;
    }

    public function listaProvicional($raw)
    {

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoEmpleadoPk = null;
        $nombreCorto = null;
        $numeroIdentificacion = null;
        $estadoContrato = null;
        $codigoGrupoFk = null;
        if ($filtros) {
            $codigoEmpleadoPk = $filtros['codigoEmpleadoPk'] ?? null;
            $codigoGrupoFk = $filtros['codigoGrupoFk'] ?? null;
            $nombreCorto = $filtros['nombreCorto'] ?? null;
            $numeroIdentificacion = $filtros['numeroIdentificacion'] ?? null;
            $estadoContrato = $filtros['estadoContrato'] ?? null;
        }

        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk')
            ->addSelect('i.nombre AS tipo')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.digitoVerificacion AS dv')
            ->addSelect('cex.nombre AS ciudad_expedicion_identificacion')
            ->addSelect('e.fechaExpedicionIdentificacion')
            ->addSelect('e.libretaMilitar')
            ->addSelect('g.nombre AS grupo')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.telefono')
            ->addSelect('e.celular')
            ->addSelect('e.direccion')
            ->addSelect('e.barrio')
            ->addSelect('e.codigoRhFk')
            ->addSelect('s.nombre AS genero')
            ->addSelect('e.correo')
            ->addSelect('e.fechaNacimiento')
            ->addSelect('cin.nombre AS ciudad_nacimiento')
            ->addSelect('ci.nombre AS ciudad')
            ->addSelect('ec.nombre AS estado_civil')
            ->addSelect('e.padreFamilia')
            ->addSelect('e.cabezaHogar')
            ->addSelect('es.nombreCorto AS entidad_salud')
            ->addSelect('ep.nombreCorto AS entidad_pension')
            ->addSelect('eca.nombreCorto AS entidad_caja')
            ->addSelect('clr.nombre AS clasificacion_riesgo')
            ->addSelect('e.cuenta')
            ->addSelect('b.nombre AS banco')
            ->addSelect('c.fechaDesde AS fecha_desde_contrato')
            ->addSelect('c.fechaHasta AS fecha_finalizacion_contrato')
            ->addSelect('car.nombre AS cargo')
            ->addSelect('tp.nombre AS tipo_pension')
            ->addSelect('tc.nombre AS tipo_cotizante')
            ->addSelect('stc.nombre AS sub_tipo_cotizante')
            ->addSelect('e.estadoContrato')
            ->addSelect('e.codigoContratoFk')
            ->addSelect('e.tallaCamisa')
            ->addSelect('e.tallaPantalon')
            ->addSelect('e.tallaCalzado')
            ->addSelect('e.discapacidad')
            ->leftJoin('e.contratoRel', 'c')
            ->leftJoin('e.ciudadRel', 'ci')
            ->leftJoin('e.ciudadExpedicionRel', 'cex')
            ->leftJoin('e.ciudadNacimientoRel', 'cin')
            ->leftJoin('e.bancoRel', 'b')
            ->leftJoin('c.grupoRel', 'g')
            ->leftJoin('c.entidadSaludRel', 'es')
            ->leftJoin('c.entidadPensionRel', 'ep')
            ->leftJoin('c.entidadCajaRel', 'eca')
            ->leftJoin('e.identificacionRel', 'i')
            ->leftJoin('e.sexoRel', 's')
            ->leftJoin('e.estadoCivilRel', 'ec')
            ->leftJoin('c.clasificacionRiesgoRel', 'clr')
            ->leftJoin('c.cargoRel', 'car')
            ->leftJoin('c.pensionRel', 'tp')
            ->leftJoin('c.tipoCotizanteRel', 'tc')
            ->leftJoin('c.subtipoCotizanteRel', 'stc')
            ->where('e.codigoEmpleadoPk <> 0');
        if ($codigoEmpleadoPk) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$codigoEmpleadoPk}");
        }
        if ($nombreCorto) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$nombreCorto}%'");
        }
        if ($numeroIdentificacion) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$numeroIdentificacion}' ");
        }
        if ($codigoGrupoFk) {
            $queryBuilder->andWhere("c.codigoGrupoFk = '{$codigoGrupoFk}' ");
        }
        switch ($estadoContrato) {
            case '0':
                $queryBuilder->andWhere("e.estadoContrato = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoContrato = 1");
                break;
        }
        $queryBuilder->addOrderBy('e.codigoEmpleadoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function parametrosExcel()
    {
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk')
            ->addSelect('e.codigoIdentificacionFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('ce.nombre AS ciudadExpedicion')
            ->addSelect('e.nombre1')
            ->addSelect('e.nombre2')
            ->addSelect('e.apellido1')
            ->addSelect('e.apellido2')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.telefono')
            ->addSelect('e.correo')
            ->addSelect('e.direccion')
            ->addSelect('e.barrio')
            ->addSelect('e.correo')
            ->addSelect('b.nombre AS banco')
            ->addSelect('e.codigoCuentaTipoFk')
            ->addSelect('e.cuenta')
            ->addSelect('e.codigoRhFk')
            ->addSelect('e.codigoSexoFk')
            ->addSelect('e.codigoEstadoCivilFk')
            ->leftJoin('e.ciudadRel', 'c')
            ->leftJoin('e.ciudadNacimientoRel', 'cn')
            ->leftJoin('e.ciudadExpedicionRel', 'ce')
            ->leftJoin('e.bancoRel', 'b')
            ->where('e.codigoEmpleadoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function listaBuscarTurno()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->addSelect('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('ca.nombre as cargo')
            ->addSelect('c.estadoTerminado')
            ->where('c.habilitadoTurno = 1')
            ->leftJoin('c.empleadoRel', 'e')
            ->leftJoin('e.cargoRel', 'ca');
        if ($session->get('filtroTurEmpleadoCodigo')) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroTurEmpleadoCodigo')}");
        }
        if ($session->get('filtroTurEmpleadoNombre')) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroTurEmpleadoNombre')}%'");
        }
        if ($session->get('filtroTurEmpleadoIdentificacion')) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroTurEmpleadoIdentificacion')}' ");
        }
        return $queryBuilder;
    }

    public function turnoBuscarPrototipo()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->addSelect('car.nombre as cargoNombre')
            ->where('c.habilitadoTurno = 1')
            ->leftJoin('e.contratoRel', 'c')
            ->leftJoin('c.cargoRel', 'car');
        if ($session->get('filtroTurEmpleadoCodigo')) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroTurEmpleadoCodigo')}");
        }
        if ($session->get('filtroTurEmpleadoNombre')) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroTurEmpleadoNombre')}%'");
        }
        if ($session->get('filtroTurEmpleadoIdentificacion')) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroTurEmpleadoIdentificacion')}' ");
        }
        return $queryBuilder;
    }

    public function listaBuscarCapacitacion()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->addSelect('car.nombre as cargo')
            ->addSelect('g.nombre AS grupo')
            ->where('e.estadoContrato = 1')
            ->leftJoin('e.contratoRel', 'c')
            ->leftJoin('c.cargoRel', 'car')
        ->leftJoin('c.grupoRel', 'g');
        if ($session->get('filtroTurEmpleadoCodigo')) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroTurEmpleadoCodigo')}");
        }
        if ($session->get('filtroTurEmpleadoNombre')) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroTurEmpleadoNombre')}%'");
        }
        if ($session->get('filtroTurEmpleadoIdentificacion')) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroTurEmpleadoIdentificacion')}' ");
        }
        return $queryBuilder;
    }

    public function listaBuscarProgramacion()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class, 'c')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->addSelect('c.codigoContratoPk')
            ->addSelect('c.fechaDesde')
            ->addSelect('c.fechaHasta')
            ->addSelect('c.estadoTerminado')
            ->addSelect('ca.nombre as cargo')
            ->where('c.habilitadoTurno = 1')
            ->leftJoin('c.empleadoRel', 'e')
            ->leftJoin('c.cargoRel', 'ca');
        if ($session->get('filtroTurPedidoDetalleCodigo')) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroTurPedidoDetalleCodigo')}");
        }
        if ($session->get('filtroTurPedidoDetalleNombre')) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroTurPedidoDetalleNombre')}%'");
        }
        if ($session->get('filtroTurPedidoDetalleIdentificacion')) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroTurPedidoDetalleIdentificacion')}' ");
        }
        switch ($session->get('filtroTurPedidoDetalleEmpleadoContratado')) {
            case '0':
                $queryBuilder->andWhere("e.estadoContrato = 1");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoContrato = 0");
                break;
        }
        return $queryBuilder;
    }

    public function terceroTesoreria($arEmpleado)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        if ($arEmpleado) {
            $arTercero = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arEmpleado->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion()));
            if (!$arTercero) {
                $arTercero = new TesTercero();
                $arTercero->setIdentificacionRel($arEmpleado->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arEmpleado->getNombreCorto());
                $arTercero->setDireccion($arEmpleado->getDireccion());
                $arTercero->setTelefono($arEmpleado->getTelefono());
                //$arTercero->setEmail($arCliente->getCorreo());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

    public function empleadoIntercambio($codigoEmpleado)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk')
            ->addSelect('e.codigoCuentaTipoFk')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('c.nombre as lugarExpedicion')
            ->addSelect('e.nombre1')
            ->addSelect('e.nombre2')
            ->addSelect('e.apellido1')
            ->addSelect('e.apellido2')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.correo')
            ->leftJoin('e.ciudadExpedicionRel', 'c')
            ->where("e.codigoEmpleadoPk = {$codigoEmpleado}");

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function terceroFinanciero($codigo)
    {
        /**
         * @var $arEmpleado RhuEmpleado
         */
        $em = $this->getEntityManager();
        $arTercero = null;
        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigo);
        if ($arEmpleado) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arEmpleado->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion()));
            if (!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arEmpleado->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                $arTercero->setCiudadRel($arEmpleado->getCiudadRel());
                $arTercero->setNombreCorto($arEmpleado->getNombreCorto());
                $arTercero->setNombre1($arEmpleado->getNombre1());
                $arTercero->setNombre2($arEmpleado->getNombre2());
                $arTercero->setApellido1($arEmpleado->getApellido1());
                $arTercero->setApellido2($arEmpleado->getApellido2());
                $arTercero->setDireccion($arEmpleado->getDireccion());
                $arTercero->setTelefono($arEmpleado->getTelefono());
                $arTercero->setCelular($arEmpleado->getCelular());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

    /**
     * @todo: buscar avanzado de la ruta turno_buscar_empleado
     */
    public function ListaBuscarEmpleado($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoEmpelado = null;
        $nombreCorto = null;
        $numeroIdentificacion = null;
        $estadoContrato = null;

        if ($filtros) {
            $codigoEmpelado = $filtros['codigoEmpelado'] ?? null;
            $nombreCorto = $filtros['nombreCorto'] ?? null;
            $numeroIdentificacion = $filtros['numeroIdentificacion'] ?? null;
            $estadoContrato = $filtros['estadoContrato'] ?? null;
        }

        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->where('e.codigoEmpleadoPk <> 0');
        if ($codigoEmpelado) {
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$codigoEmpelado}");
        }
        if ($nombreCorto) {
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$nombreCorto}%'");
        }
        if ($numeroIdentificacion) {
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$numeroIdentificacion}' ");
        }
        switch ($estadoContrato) {
            case '0':
                $queryBuilder->andWhere("e.estadoContrato = 1");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoContrato = 0");
                break;
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $ar = $em->getRepository(RhuEmpleado::class)->find($codigo);
                    if ($ar) {
                        $em->remove($ar);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }
    }

    public function ValidarNumeroIdentificacion($arEmpleado)
    {
        if ($arEmpleado) {
            if ($arEmpleado->getCodigoEmpleadoPK()) {
                $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class, 'e')
                    ->select('e')
                    ->where("e.numeroIdentificacion = {$arEmpleado->getNumeroIdentificacion()}")
                    ->andWhere("e.codigoEmpleadoPk <> {$arEmpleado->getCodigoEmpleadoPK()}");
                $arEmpleado = $queryBuilder->getQuery()->getResult();
            } else {
                $arEmpleado = $this->_em->getRepository(RhuEmpleado::class)->findBy(['numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion()]);
            }
            if (!$arEmpleado) {
                return true;
            } else {
                Mensajes::error("Ya existe un empleado con número de identificación");
                return false;
            }
        } else {
            Mensajes::error("Es necesario un número de identificación");
            return false;
        }

    }


    public function autoCompletar($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'];
        $filtros = $raw['filtros'] ?? null;
        $nombreCorto = null;
        $numeroIdentificacion = null;
        if ($filtros) {
            $nombreCorto = $filtros['nombreCorto'] ?? null;
            $numeroIdentificacion = $filtros['numeroIdentificacion'] ?? null;
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuEmpleado::class, 'e')
            ->select('e.codigoEmpleadoPk as value')
            ->addSelect("CONCAT(e.nombreCorto, ' ', e.numeroIdentificacion)  as label")
            ->orderBy('e.nombreCorto', 'ASC');
        if ($nombreCorto) {
            $queryBuilder->orWhere("e.nombreCorto LIKE '%{$nombreCorto}%'");
        }
        if ($numeroIdentificacion) {
            $queryBuilder->orWhere("e.numeroIdentificacion LIKE '%{$numeroIdentificacion}%'");
        }
        $queryBuilder->addOrderBy('e.codigoEmpleadoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }
}

