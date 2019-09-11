<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamen;
use App\Entity\RecursoHumano\RhuExamenDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RhuExamenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RhuExamen::class);
    }


    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuExamen::class, 'e')
            ->select('e.codigoExamenPk')
            ->addSelect('ec.nombre as examenClase')
            ->addSelect('e.fecha')
            ->addSelect('e.numeroIdentificacion')
            ->addSelect('e.nombreCorto')
            ->addSelect('ee.nombre as entidadExamen')
            ->addSelect('c.nombre as cargo')
            ->addSelect('e.cobro')
            ->addSelect('e.vrTotal')
            ->addSelect('e.estadoAutorizado')
            ->addSelect('e.estadoAprobado')
            ->addSelect('e.estadoAnulado')
            ->leftJoin('e.examenClaseRel' ,'ec')
            ->leftJoin('e.entidadExamenRel' ,'ee')
            ->leftJoin('e.cargoRel' ,'c');

        if ($session->get('RhuExamen_codigoExamenPk')) {
            $queryBuilder->andWhere("e.codigoExamenPk = '{$session->get('RhuExamen_codigoExamenPk')}'");
        }

        if ($session->get('RhuExamen_codigoExamenClaseFk')) {
            $queryBuilder->andWhere("e.codigoExamenClaseFk = '{$session->get('RhuExamen_codigoExamenClaseFk')}'");
        }

        if ($session->get('RhuExamen_codigoEmpleadoFk')) {
            $queryBuilder->andWhere("e.codigoEmpleadoFk = '{$session->get('RhuExamen_codigoEmpleadoFk')}'");
        }

        if ($session->get('RhuExamen_fechaDesde') != null) {
            $queryBuilder->andWhere("e.fechaDesde >= '{$session->get('RhuExamen_fechaDesde')} 00:00:00'");
        }

        if ($session->get('RhuExamen_fechaHasta') != null) {
            $queryBuilder->andWhere("e.fecha <= '{$session->get('RhuExamen_fechaHasta')} 23:59:59'");
        }

        switch ($session->get('RhuExamen_estadoAutorizado')) {
            case '0':
                $queryBuilder->andWhere("e.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAutorizado = 1");
                break;
        }

        switch ($session->get('RhuExamen_estadoAprobado')) {
            case '0':
                $queryBuilder->andWhere("e.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAprobado = 1");
                break;
        }

        switch ($session->get('RhuExamen_estadoAnulado')) {
            case '0':
                $queryBuilder->andWhere("e.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("e.estadoAnulado = 1");
                break;
        }
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
}