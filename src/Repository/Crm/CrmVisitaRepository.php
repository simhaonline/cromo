<?php

namespace App\Repository\Crm;

use App\Entity\Crm\CrmVisita;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CrmVisitaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrmVisita::class);
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(CrmVisita::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() != 0) {
                            $respuesta = 'No se puede eliminar, el registro se encuentra autorizado';
                        }
                    } else {
                        $respuesta = 'No se puede eliminar, el registro se encuentra aprobado';
                    }
                    if ($respuesta != '') {
                        Mensajes::error($respuesta);
                    } else {
                        $this->getEntityManager()->remove($arRegistro);
                        $this->getEntityManager()->flush();
                    }
                }
            }
        }
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoVisita = null;
        $visitaTipo = null;
        $contacto = null;
        $codigoCliente = null;
        $fechaDesde = null;
        $fechaHasta = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoVisita = $filtros['codigoVisita'] ?? null;
            $visitaTipo = $filtros['visitaTipo'] ?? null;
            $contacto = $filtros['contacto'] ?? null;
            $codigoCliente = $filtros['codigoCliente'] ?? null;
            $fechaDesde = $filtros['fechaDesde'] ?? null;
            $fechaHasta = $filtros['fechaHasta'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CrmVisita::class, 'v')
            ->select('v.codigoVisitaPk')
            ->addSelect('v.fecha')
            ->addSelect('vt.nombre AS tipo')
            ->addSelect('c.nombreCorto AS cliente')
            ->addSelect('con.nombreCorto AS contacto')
            ->addSelect('v.comentarios')
            ->addSelect('v.estadoAutorizado')
            ->addSelect('v.estadoAprobado')
            ->addSelect('v.estadoAnulado')
            ->leftJoin('v.visitaTipoRel', 'vt')
            ->leftJoin('v.contactoRel', 'con')
            ->leftJoin('v.clienteRel', 'c');
        if ($codigoVisita) {
            $queryBuilder->andWhere("v.codigoVisitaPk = '{$codigoVisita}'");
        }
        if ($contacto) {
            $queryBuilder->andWhere("v.codigoContactoFk = '{$contacto}'");
        }
        if ($codigoCliente) {
            $queryBuilder->andWhere("v.codigoClienteFk = '{$codigoCliente}'");
        }
        if ($visitaTipo) {
            $queryBuilder->andWhere("v.codigoVisitaTipoFk = '{$visitaTipo}'");
        }
        if ($fechaDesde) {
            $queryBuilder->andWhere("v.fecha >= '{$fechaDesde} 00:00:00'");
        }
        if ($fechaHasta) {
            $queryBuilder->andWhere("v.fecha <= '{$fechaHasta} 23:59:59'");
        }
        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("v.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("v.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('v.codigoVisitaPk', 'ASC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function autorizar($arVisita)
    {
        $em = $this->getEntityManager();
        if ($arVisita->getEstadoAutorizado() == 0) {

            $arVisita->setEstadoAutorizado(1);
            $em->persist($arVisita);
            $em->flush();

        } else {
            Mensajes::error('La visita ya esta autorizado');
        }
    }

    public function desautorizar($arVisita)
    {
        $em = $this->getEntityManager();
        if ($arVisita->getEstadoAutorizado() == 1) {

            $arVisita->setEstadoAutorizado(0);
            $em->persist($arVisita);
            $em->flush();

        } else {
            Mensajes::error('La visita ya esta desautorizada');
        }
    }

    public function aprobar($arVisita)
    {
        $em = $this->getEntityManager();
        if ($arVisita->getEstadoAutorizado() == 1) {
            if ($arVisita->getEstadoAprobado() == 0) {
                $arVisita->setEstadoAprobado(1);
                $em->persist($arVisita);
                $em->flush();
            } else {
                Mensajes::error('La visita ya esta aprobada');
            }

        } else {
            Mensajes::error('La visita ya esta desautorizada');
        }
    }

    public function anular($arVisita)
    {
        $em = $this->getEntityManager();
        if ($arVisita->getEstadoAutorizado() == 1) {
            if ($arVisita->getEstadoAnulado() == 0) {
                $arVisita->setEstadoAnulado(1);
                $em->persist($arVisita);
                $em->flush();
            } else {
                Mensajes::error('La visita ya esta anulado');
            }

        } else {
            Mensajes::error('La visita no esta autorizada');
        }
    }

}
