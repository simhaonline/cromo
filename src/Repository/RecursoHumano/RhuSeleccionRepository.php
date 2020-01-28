<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionEntrevista;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuSeleccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSeleccion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSeleccion = null;
        $nombre = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoSeleccion = $filtros['codigoSeleccion'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSeleccion::class, 's')
            ->select('s.codigoSeleccionPk')
            ->addSelect('s.numeroIdentificacion')
            ->addSelect('s.nombreCorto')
            ->addSelect('s.telefono')
            ->addSelect('s.celular')
            ->addSelect('s.correo')
            ->addSelect('s.direccion')
            ->addSelect('s.estadoAutorizado')
            ->addSelect('s.estadoAprobado')
            ->addSelect('s.estadoAnulado');
        if ($codigoSeleccion) {
            $queryBuilder->andWhere("s.codigoSeleccionPk = '{$codigoSeleccion}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("s.nombreCorto LIKE '%{$nombre}%'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("s.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("s.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('s.codigoSeleccionPk', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arSeleccionado
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function autorizar($arSeleccion)
    {
        $em = $this->getEntityManager();
        if (!$arSeleccion->getEstadoAutorizado()) {
            $arSeleccionEntrevista =  $em->getRepository(RhuSeleccionEntrevista::class)->findBy(array('codigoSeleccionFk' => $arSeleccion));
            if($arSeleccionEntrevista){
                $arSeleccion->setEstadoAutorizado(1);
                $em->persist($arSeleccion);
                $em->flush();
            } else {
                Mensajes::error("La selección no tiene entrevistas, no se puede autorizar");
            }
        } else {
            Mensajes::error('El selección ya esta autorizado');
        }
    }

    public function desautorizar($arSeleccion)
    {
        if ($arSeleccion->getEstadoAutorizado() == 1 && $arSeleccion->getEstadoAprobado() == 0) {
            $arSeleccion->setEstadoAutorizado(0);
            $this->getEntityManager()->persist($arSeleccion);
            $this->getEntityManager()->flush();
        } else {
            Mensajes::error('El registro no se puede desautorizar');
        }
    }

    public function Anular($arSeleccionado)
    {
        $em = $this->getEntityManager();
        $arSeleccionado->setEstadoAnulado(1);
        $em->persist($arSeleccionado);
        $em->flush();
    }

    public function Aprobar($arSeleccionado)
    {
        $em = $this->getEntityManager();
        if (!$arSeleccionado->getEstadoAprobado()) {
            $arSeleccionado->setEstadoAprobado(1);
            $em->persist($arSeleccionado);
            $em->flush();
        } else {
            Mensajes::error('El seleccion ya esta aprobado');
        }
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:RecursoHumano\RhuSeleccion', 's')
            ->select('s');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

}