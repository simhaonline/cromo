<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteRecogida;
use App\Entity\Transporte\TteRecogidaProgramada;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TteRecogidaProgramadaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteRecogidaProgramada::class);
    }

    public function lista(): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT rp.codigoRecogidaProgramadaPk, c.nombreCorto AS clienteNombreCorto, co.nombre AS ciudad,
            rp.anunciante, rp.hora, rp.codigoOperacionFk, rp.direccion, rp.telefono, r.nombre, rp.fechaUltimaGenerada
        FROM App\Entity\Transporte\TteRecogidaProgramada rp 
        LEFT JOIN rp.rutaRecogidaRel r
        LEFT JOIN rp.clienteRel c
        LEFT JOIN rp.ciudadRel co'
        );
        return $query->execute();

    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function eliminar($arrSeleccionados)
    {
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $ar = $this->getEntityManager()->getRepository(TteRecogidaProgramada::class)->find($arrSeleccionado);
                if ($ar) {
                    $this->getEntityManager()->remove($ar);
                }
            }
            $this->getEntityManager()->flush();
        }
    }

    public function generarTodos()
    {
        $em = $this->getEntityManager();
        $queryBuilder = $em->createQueryBuilder()->from(TteRecogidaProgramada::class, 'rp')
            ->select('rp.codigoRecogidaProgramadaPk');
        $arRecogidasProgramadas = $queryBuilder->getQuery()->getResult();
        foreach ($arRecogidasProgramadas as $arRecogidasProgramada) {
            $this->generarRecogidaProgramada($arRecogidasProgramada['codigoRecogidaProgramadaPk']);
        }
        $em->flush();
    }

    public function generarSeleccionados($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $this->generarRecogidaProgramada($codigo);
            }
        }
        $em->flush();
    }

    public function generarRecogidaProgramada($codigoRecogidaProgramada)
    {
        /**
         * @var $arRecogidasProgramadas TteRecogidaProgramada
         */
        $em = $this->getEntityManager();
        $arRecogidaProgramada = $em->getRepository(TteRecogidaProgramada::class)->find($codigoRecogidaProgramada);
        $cliente = $arRecogidaProgramada->getClienteRel()->getNombreCorto();
        $fecha = new \DateTime('now');
        $fechaDia = date_create($fecha->format('Y-m-d'));

        $fechaHora = $fecha->format('Y-m-d');
//        $arRecogidaYaProgramada = $em->getRepository(TteRecogida::class)->yaProgramada($fechaHora, $arRecogidaProgramada->getCodigoClienteFk(), $arRecogidaProgramada->getCodigoRutaRecogidaFk());
//        if ($arRecogidaYaProgramada) {
//            Mensajes::error("Ya existe un registro de recogida para esta fecha .$codigoRecogidaProgramada");
//        } else {
            if($fechaDia > $arRecogidaProgramada->getFechaUltimaGenerada()){
                $arRecogida = new TteRecogida();
                $fechaRecogida = date_create($fechaHora . " " . $arRecogidaProgramada->getHora()->format('H:i'));
                $arRecogida->setFechaRegistro(new \DateTime('now'));
                $arRecogida->setFecha($fechaRecogida);
                $arRecogida->setClienteRel($arRecogidaProgramada->getClienteRel());
                $arRecogida->setRutaRecogidaRel($arRecogidaProgramada->getRutaRecogidaRel());
                $arRecogida->setOperacionRel($arRecogidaProgramada->getOperacionRel());
                $arRecogida->setCiudadRel($arRecogidaProgramada->getCiudadRel());
                $arRecogida->setAnunciante($arRecogidaProgramada->getAnunciante());
                $arRecogida->setDireccion($arRecogidaProgramada->getDireccion());
                $arRecogida->setTelefono($arRecogidaProgramada->getTelefono());
                $arRecogida->setComentario($arRecogidaProgramada->getComentario());
                $arRecogida->setEstadoAutorizado(1);
                $arRecogida->setEstadoAprobado(1);
                $arRecogidaProgramada->setFechaUltimaGenerada(new \DateTime('now'));
                $em->persist($arRecogida);
                $em->persist($arRecogidaProgramada);
            } else {
                Mensajes::error("Ya se generaron las recogidas para el cliente {$arRecogidaProgramada->getClienteRel()->getNombreCorto()}  para la fecha {$fecha->format('Y-m-d')} ; ");
            }

        $em->flush();
    }

}