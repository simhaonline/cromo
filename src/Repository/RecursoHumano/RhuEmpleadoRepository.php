<?php

namespace App\Repository\RecursoHumano;

use App\Entity\Financiero\FinTercero;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\Tesoreria\TesTercero;
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

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class,'e')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.estadoContrato')
            ->where('e.codigoEmpleadoPk <> 0');
        if($session->get('filtroRhuEmpleadoCodigo')){
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroRhuEmpleadoCodigo')}");
        }
        if($session->get('filtroRhuEmpleadoNombre')){
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroRhuEmpleadoNombre')}%'");
        }
        if($session->get('filtroRhuEmpleadoIdentificacion')){
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

    public function listaProvicional($raw){

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoEmpleadoPk = null;
        $nombreCorto = null;
        $numeroIdentificacion =null;
        $estadoContrato = null;
        if ($filtros){
            $codigoEmpleadoPk = $filtros['codigoEmpleadoPk']??null;
            $nombreCorto = $filtros['nombreCorto']??null;
            $numeroIdentificacion = $filtros['numeroIdentificacion']??null;
            $estadoContrato = $filtros['estadoContrato']??null;
        }

        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuEmpleado::class,'e')
            ->select('e.codigoContratoFk')
            ->addSelect('e.codigoEmpleadoPk')
            ->addSelect('e.nombreCorto')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.telefono')
            ->addSelect('e.correo')
            ->addSelect('e.direccion')
            ->addSelect('e.estadoContrato')
            ->where('e.codigoEmpleadoPk <> 0');
        if($codigoEmpleadoPk){
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$codigoEmpleadoPk}");
        }
        if($nombreCorto){
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$nombreCorto}%'");
        }
        if($numeroIdentificacion){
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$numeroIdentificacion}' ");
        }
        switch ($estadoContrato ) {
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
            ->leftJoin('e.bancoRel','b')
            ->where('e.codigoEmpleadoPk <> 0');
        return $queryBuilder->getQuery()->execute();
    }

    public function listaBuscarTurno(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class,'c')
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
        if($session->get('filtroTurEmpleadoCodigo')){
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroTurEmpleadoCodigo')}");
        }
        if($session->get('filtroTurEmpleadoNombre')){
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroTurEmpleadoNombre')}%'");
        }
        if($session->get('filtroTurEmpleadoIdentificacion')){
            $queryBuilder->andWhere("e.numeroIdentificacion = '{$session->get('filtroTurEmpleadoIdentificacion')}' ");
        }
        return $queryBuilder;
    }

    public function listaBuscarProgramacion(){
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(RhuContrato::class,'c')
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
            ->leftJoin('e.cargoRel', 'ca');
        if($session->get('filtroTurPedidoDetalleCodigo')){
            $queryBuilder->andWhere("e.codigoEmpleadoPk = {$session->get('filtroTurPedidoDetalleCodigo')}");
        }
        if($session->get('filtroTurPedidoDetalleNombre')){
            $queryBuilder->andWhere("e.nombreCorto LIKE '%{$session->get('filtroTurPedidoDetalleNombre')}%'");
        }
        if($session->get('filtroTurPedidoDetalleIdentificacion')){
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
        if($arEmpleado) {
            $arTercero = $em->getRepository(TesTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arEmpleado->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion()));
            if(!$arTercero) {
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

    public function empleadoIntercambio($codigoEmpleado){
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
        $em = $this->getEntityManager();
        $arTercero = null;
        $arEmpleado = $em->getRepository(RhuEmpleado::class)->find($codigo);
        if($arEmpleado) {
            $arTercero = $em->getRepository(FinTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arEmpleado->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arEmpleado->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new FinTercero();
                $arTercero->setIdentificacionRel($arEmpleado->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arEmpleado->getNombreCorto());
                $arTercero->setDireccion($arEmpleado->getDireccion());
                $arTercero->setTelefono($arEmpleado->getTelefono());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }
}