<?php

namespace App\Repository\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use App\Entity\RecursoHumano\RhuSeleccion;
use App\Entity\RecursoHumano\RhuSeleccionTipo;
use App\Entity\RecursoHumano\RhuSolicitud;
use App\Entity\RecursoHumano\RhuSolicitudAspirante;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class RhuSolicitudAspiranteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RhuSolicitudAspirante::class);
    }

    public function lista($raw, $id)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoSolicitud = null;
        $nombre = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoSolicitud = $filtros['codigoSolicitud'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(RhuSolicitudAspirante::class, 'sa')
            ->select('sa.codigoSolicitudAspirantePk')
            ->addSelect('a.numeroIdentificacion')
            ->addSelect('a.nombreCorto AS aspirante')
            ->addSelect('sa.estadoGenerado')
            ->addSelect('sa.comentarios')
            ->where("sa.codigoSolicitudFk = '{$id}'")
            ->leftJoin('sa.aspiranteRel', 'a');
        if ($codigoSolicitud) {
            $queryBuilder->andWhere("s.codigoSolicitudPk = '{$codigoSolicitud}'");
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
        $queryBuilder->addOrderBy('sa.codigoSolicitudAspirantePk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function aprobarDetallesSeleccionados($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            $arSeleccionTipo = $em->getRepository(RhuSeleccionTipo::class)->find('SDP');
            foreach ($arrSeleccionados AS $codigoSolicitudAspirante) {
                $arSolicitudAspirante = $em->getRepository(RhuSolicitudAspirante::class)->find($codigoSolicitudAspirante);
                if ($arSolicitudAspirante) {
                    if ($arSolicitudAspirante->getEstadoGenerado() == 0) {
                        $arSolicitudAspirante->setEstadoGenerado(1);
                        $arAspitante = $em->getRepository(RhuAspirante::class)->find($arSolicitudAspirante->getCodigoAspiranteFk());
                        //Se inserta el aspirante aprobado en la entidad seleccion
                        $arSelecion = new RhuSeleccion();
                        $arSelecion->setFecha(new \ DateTime("now"));
                        $arSelecion->setCiudadRel($arAspitante->getCiudadRel());
                        $arSelecion->setCiudadExpedicionRel($arAspitante->getCiudadExpedicionRel());
                        $arSelecion->setCiudadNacimientoRel($arAspitante->getCiudadNacimientoRel());
                        $arSelecion->setIdentificacionRel($arAspitante->getIdentificacionRel());
                        $arSelecion->setEstadoCivilRel($arAspitante->getEstadoCivilRel());
                        $arSelecion->setNumeroIdentificacion($arAspitante->getNumeroIdentificacion());
                        $arSelecion->setFechaNacimiento($arAspitante->getFechaNacimiento());
                        $arSelecion->setSexoRel($arAspitante->getSexoRel());
                        $arSelecion->setRhRel($arAspitante->getRhRel());
                        $arSelecion->setNombreCorto($arAspitante->getNombreCorto());
                        $arSelecion->setNombre1($arAspitante->getNombre1());
                        $arSelecion->setNombre2($arAspitante->getNombre2());
                        $arSelecion->setApellido1($arAspitante->getApellido1());
                        $arSelecion->setApellido2($arAspitante->getApellido2());
                        $arSelecion->setTelefono($arAspitante->getTelefono());
                        $arSelecion->setCelular($arAspitante->getCelular());
                        $arSelecion->setDireccion($arAspitante->getDireccion());
                        $arSelecion->setSolicitudRel($arSolicitudAspirante->getSolicitudRel());
                        $arSelecion->setZonaRel($arAspitante->getZonaRel());
                        $arSelecion->setSeleccionTipoRel($arSeleccionTipo);
                        $em->persist($arSelecion);
//                        $arSelecion->setCargoRel($arSolicitudAspirante->getSeleccionRequisitoRel()->getCargoRel());
                    } else {
                        Mensajes::error("El aspirante ya tiene un proceso de seleccion");
                    }
                }
            }
        }
        $em->flush();
    }

}