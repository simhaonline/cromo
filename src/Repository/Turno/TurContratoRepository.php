<?php

namespace App\Repository\Turno;


use App\Entity\Turno\TurContrato;
use App\Entity\Turno\TurContratoDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TurContratoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TurContrato::class);
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
                $arRegistro = $this->getEntityManager()->getRepository(TurContrato::class)->find($codigo);
                if ($arRegistro) {
                    if ($arRegistro->getEstadoAprobado() == 0) {
                        if ($arRegistro->getEstadoAutorizado() == 0) {
                            if (count($this->getEntityManager()->getRepository(TurContratoDetalle::class)->findBy(['codigoContratoFk' => $arRegistro->getCodigoContratoPk()])) <= 0) {
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
     * @param $codigoContrato
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function contarDetalles($codigoContrato)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TurContratoDetalle::class, 'cd')
            ->select("COUNT(cd.codigoContratoDetallePk)")
            ->where("cd.codigoContratoFk = {$codigoContrato} ");
        $resultado = $queryBuilder->getQuery()->getSingleResult();
        return $resultado[1];
    }

    /**
     * @param $arContratos TurContrato
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function liquidar($id)
    {
        $em = $this->getEntityManager();
        $vrSubtotalGlobal = 0;
        $vrTotalBrutoGlobal = 0;
        $vrIvaGlobal = 0;
        $vrSalarioBaseGlobal = 0;
        $vrTotalCostoCalculado = 0;
        $totalHoras = 0;
        $totalHorasDiurnas = 0;
        $totalHorasNocturnas = 0;
        $arContratos = $em->getRepository(TurContrato::class)->find($id);
        $arContratoDetalles = $this->getEntityManager()->getRepository(TurContratoDetalle::class)->findBy(array('codigoContratoFk' => $arContratos->getCodigoContratoPk()));
        foreach ($arContratoDetalles as $arContratoDetalle) {
            $horas = $arContratoDetalle->getHoras();
            $totalHoras += $horas;
            $horasDiurnas = $arContratoDetalle->getHorasDiurnas();
            $totalHorasDiurnas += $horasDiurnas;
            $horasNocturnas = $arContratoDetalle->getHorasNocturnas();
            $totalHorasNocturnas += $horasNocturnas;
            $vrSubtotal = $arContratoDetalle->getVrSubtotal();
            $vrSubtotalGlobal += $vrSubtotal;
            $vrTotal = $arContratoDetalle->getVrTotalDetalle();
            $vrTotalBrutoGlobal += $vrTotal;
            $vrIva = $arContratoDetalle->getVrIva();
            $vrIvaGlobal += $vrIva;
            $vrSalarioBase = $arContratoDetalle->getVrSalarioBase();
            $vrSalarioBaseGlobal += $vrSalarioBase;
            $vrCosto = $arContratoDetalle->getVrCosto();
            $vrTotalCostoCalculado += $vrCosto;

        }
        $arContratos->setVrSubtotal($vrSubtotalGlobal);
        $arContratos->setVrTotal($vrTotalBrutoGlobal);
        $arContratos->setVrIva($vrIvaGlobal);
        $arContratos->setVrTotalServicio($vrTotalBrutoGlobal);
        $arContratos->setVrSalarioBase($vrSalarioBaseGlobal);
        $arContratos->setVrTotalCosto($vrTotalCostoCalculado);
        $arContratos->setHoras($totalHoras);
        $arContratos->setHorasDiurnas($totalHorasDiurnas);
        $arContratos->setHorasNocturnas($totalHorasNocturnas);

        $em->persist($arContratos);
        $em->flush();
    }
}
