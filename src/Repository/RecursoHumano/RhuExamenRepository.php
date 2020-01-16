<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Entity\RecursoHumano\RhuRequisitoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RhuExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuExamen::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoExamen = null;
        $examenTipo = null;
        $codigoEmpleado = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoExamen = $filtros['codigoExamen'] ?? null;
            $examenTipo = $filtros['examenTipo'] ?? null;
            $codigoEmpleado = $filtros['codigoEmpleado'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuExamen::class, 'e')
            ->select('e.codigoExamenPk')
            ->addSelect('et.nombre as tipo')
            ->addSelect('e.fecha')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto AS empleado')
            ->addSelect('ee.nombre as entidad')
            ->addSelect('c.nombre as cargo')
            ->addSelect('e.cobro')
            ->addSelect('e.vrTotal')
            ->addSelect('e.estadoAutorizado')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoAnulado')
            ->leftJoin('e.examenTipoRel', 'et')
            ->leftJoin('e.examenEntidadRel', 'ee')
            ->leftJoin('e.cargoRel', 'c');
        if ($codigoExamen) {
            $queryBuilder->andWhere("e.codigoExamenPk = '{$codigoExamen}'");
        }
        if ($codigoEmpleado) {
            $queryBuilder->andWhere("e.codigoEmpleadoFk = '{$codigoEmpleado}'");
        }
        if ($examenTipo) {
            $queryBuilder->andWhere("e.codigoExamenTipoFk = '{$examenTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("e.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("e.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("e.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("e.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("e.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('e.fecha', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arExamenes
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($arExamenes)
    {
        $em = $this->getEntityManager();
        $arExamen = $em->getRepository(RhuExamen::class)->find($arExamenes);
        $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $arExamenes));
        $vrTotal = 0;
        foreach ($arExamenDetalles AS $arExamenDetalle) {
            $vrTotal += $arExamenDetalle->getVrPrecio();
        }
        $arExamen->setVrTotal($vrTotal);
        $em->persist($arExamen);
        $em->flush();
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(RhuExamen::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(RhuExamenDetalle::class)->findBy(['codigoExamenFk' => $arRegistro->getCodigoExamenPk()])) <= 0) {
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
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

    /**
     * @param $codigoExamen
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($arExamenes)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuExamenDetalle::class, 'ed')
            ->select("COUNT(ed.codigoExamenDetallePk)")
            ->where("ed.codigoExamenFk = {$arExamenes} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @param $form
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function validarExamenes($form)
    {
        $em = $this->getEntityManager();
        $dql = $em->createQueryBuilder()->from(RhuExamen::class, "e")
            ->join("e.entidadExamenRel", "ee")
            ->select("e.codigoExamenPk,e.fecha")
            ->where("e.identificacion = '{$form->getIdentificacion()}'")
            ->andWhere("ee.codigoEntidadExamenPk = {$form->getEntidadExamenRel()->getCodigoEntidadExamenPk()}")
            ->getQuery()
            ->getResult();
        $result = 0;
        if (count($dql) > 0) {
            foreach ($dql as $key) {
                if ($key["fecha"]->format("Y-m-d") == $form->getFecha()->format("Y-m-d")) {
                    $result = 1;
                    break;
                }
            }
        }
        return $result;
    }

    public function autorizar($arExamen)
    {
        $em = $this->getEntityManager();
        if (!$arExamen->getEstadoAutorizado()) {
            $arExamen->setEstadoAutorizado(1);
            $em->persist($arExamen);
            $em->flush();
        } else {
            Mensajes::error('El examen ya esta autorizado');
        }
    }

    public function desAutorizar($arExamen)
    {
        $em = $this->getEntityManager();
        if ($arExamen->getEstadoAutorizado()) {
            $arExamen->setEstadoAutorizado(0);
            $em->persist($arExamen);
            $em->flush();
        }
    }

    public function aprobar($arExamen)
    {
        $em = $this->getEntityManager();
        if ($arExamen->getEstadoAprobado() == 0 && $arExamen->getEstadoAutorizado() == 1) {
            $arExamenDetalles = $em->getRepository(RhuExamenDetalle::class)->findBy(array('codigoExamenFk' => $arExamen->getCodigoExamenPk(), 'estadoAprobado' => 0));
            if (count($arExamenDetalles) <= 0) {
                $arExamen->setEstadoAprobado(1);
                $em->persist($arExamen);
                //se crea el registro del empleado en requisitos si el examen fue aprobado satisfactoriamente
                $arRequisito = new RhuRequisito();
                $arRequisito->setFecha(new \ DateTime("now"));
                $arRequisito->setCargoRel($arExamen->getCargoRel());
                $arRequisito->setNumeroIdentificacion($arExamen->getNumeroIdentificacion());
                $arRequisito->setNombreCorto($arExamen->getNombreCorto());
                $arRequisito->setUsuario($arExamen->getUsuario());
                $em->persist($arRequisito);
                $arRequisitoConceptos = $em->getRepository(RhuRequisitoConcepto::class)->findBy(array('general' => 1));
                foreach ($arRequisitoConceptos as $arRequisitoConcepto) {
                    $arRequisitoDetalle = new RhuRequisitoDetalle();
                    $arRequisitoDetalle->setRequisitoRel($arRequisito);
                    $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoConcepto);
                    $arRequisitoDetalle->setTipo('GENERAL');
                    $arRequisitoDetalle->setCantidad(1);
                    $em->persist($arRequisitoDetalle);
                }
                $arRequisitoCargo = $em->getRepository(RhuRequisitoCargo::class)->findBy(array('codigoCargoFk' => $arExamen->getCodigoCargoFk()));
                foreach ($arRequisitoCargo as $arRequisitoCargo) {
                    $arRequisitoDetalle = new RhuRequisitoDetalle();
                    $arRequisitoDetalle->setRequisitoRel($arRequisito);
                    $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoCargo->getRequisitoConceptoRel());
                    $arRequisitoDetalle->setTipo('CARGO');
                    $arRequisitoDetalle->setCantidad(1);
                    $em->persist($arRequisitoDetalle);
                }
                $em->flush();
            } else {
                Mensajes::error("Todos los detalles del examen deben estar aprobados");
            }
        } else {
            Mensajes::error("El examen ya esta aprobado o no esta autorizado");
        }
    }

}