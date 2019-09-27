<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCuentaCobrar;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CarAplicacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarAplicacion::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoAplicacion = null;
        $numeroDocumento = null;
        $numeroDocumentoAplicacion = null;
        $estadoAutorizado = null;
        $estadoAprobado = null;
        $estadoAnulado = null;

        if ($filtros) {
            $codigoAplicacion = $filtros['codigoAplicacion'] ?? null;
            $numeroDocumento = $filtros['numeroDocumento'] ?? null;
            $numeroDocumentoAplicacion = $filtros['numeroDocumentoAplicacion'] ?? null;
            $estadoAutorizado = $filtros['estadoAutorizado'] ?? null;
            $estadoAprobado = $filtros['estadoAprobado'] ?? null;
            $estadoAnulado = $filtros['estadoAnulado'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAplicacion::class, 'a')
            ->select('a.codigoAplicacionPk')
            ->addSelect('a.numeroDocumento')
            ->addSelect('a.numeroDocumentoAplicacion')
            ->addSelect('a.vrAplicacion')
            ->addSelect('a.usuario')
            ->addSelect('a.estadoAutorizado')
            ->addSelect('a.estadoAprobado')
            ->addSelect('a.estadoAnulado');
        if ($codigoAplicacion) {
            $queryBuilder->andWhere("a.codigoAplicacionPk = '{$codigoAplicacion}'");
        }
        if ($numeroDocumento) {
            $queryBuilder->andWhere("a.numeroDocumento = '{$numeroDocumento}'");
        }
        if ($numeroDocumentoAplicacion) {
            $queryBuilder->andWhere("a.numeroDocumentoAplicacion = '{$numeroDocumentoAplicacion}'");
        }

        switch ($estadoAutorizado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAutorizado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAutorizado = 1");
                break;
        }
        switch ($estadoAprobado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAprobado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAprobado = 1");
                break;
        }
        switch ($estadoAnulado) {
            case '0':
                $queryBuilder->andWhere("a.estadoAnulado = 0");
                break;
            case '1':
                $queryBuilder->andWhere("a.estadoAnulado = 1");
                break;
        }
        $queryBuilder->addOrderBy('a.codigoAplicacionPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function anular($arAplicacion)
    {
        $em = $this->getEntityManager();
        if ($arAplicacion->getEstadoAprobado() && !$arAplicacion->getEstadoAnulado()) {
            $aplicar = $arAplicacion->getVrAplicacion();

            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->find($arAplicacion->getCodigoCuentaCobrarFk());
            $saldo = $arCuentaCobrar->getVrSaldo() + $aplicar;
            $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
            $arCuentaCobrar->setVrSaldo($saldo);
            $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() - $aplicar);
            $em->persist($arCuentaCobrar);

            $arCuentaCobrarAplicar = $em->getRepository(CarCuentaCobrar::class)->find($arAplicacion->getCodigoCuentaCobrarAplicacionFk());
            $saldo = $arCuentaCobrarAplicar->getVrSaldo() + $aplicar;
            $saldoOperado = $saldo * $arCuentaCobrarAplicar->getOperacion();
            $arCuentaCobrarAplicar->setVrSaldo($saldo);
            $arCuentaCobrarAplicar->setVrSaldoOperado($saldoOperado);
            $arCuentaCobrarAplicar->setVrAbono($arCuentaCobrarAplicar->getVrAbono() - $aplicar);
            $em->persist($arCuentaCobrarAplicar);

            $arAplicacion->setEstadoAnulado(1);
            $arAplicacion->setVrAplicacion(0);
            $em->persist($arAplicacion);
            $em->flush();
        } else {
            Mensajes::error('El registro debe estar aprobado, sin anular previamente y sin contabilizar');
        }
    }

    public function referencia($codigoCuentaCobrar)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarAplicacion::class, 'a')
            ->select('a.codigoAplicacionPk')
            ->addSelect('a.numeroDocumento')
            ->addSelect('a.numeroDocumentoAplicacion')
            ->addSelect('a.estadoAnulado')
            ->addSelect('a.vrAplicacion')
            ->where('a.codigoCuentaCobrarFk = ' . $codigoCuentaCobrar)
            ->orWhere('a.codigoCuentaCobrarAplicacionFk = ' . $codigoCuentaCobrar);
        return $queryBuilder->getQuery()->getResult();
    }

    public function aplicar($arCuentaCobrarAplicar, $modulo, $codigoDocumento) {
        $em = $this->getEntityManager();
        if($codigoDocumento && $modulo) {
            $arCuentaCobrar = $em->getRepository(CarCuentaCobrar::class)->findOneBy(array('modulo' => $modulo, 'codigoDocumento' => $codigoDocumento));
            if($arCuentaCobrar && $arCuentaCobrarAplicar) {
                $vrAplicar = $arCuentaCobrarAplicar->getVrSaldo();
                if ($arCuentaCobrar->getVrSaldo() >= $vrAplicar) {
                    $saldo = $arCuentaCobrar->getVrSaldo() - $vrAplicar;
                    $saldoOperado = $saldo * $arCuentaCobrar->getOperacion();
                    $arCuentaCobrar->setVrSaldo($saldo);
                    $arCuentaCobrar->setVrSaldoOperado($saldoOperado);
                    $arCuentaCobrar->setVrAbono($arCuentaCobrar->getVrAbono() + $vrAplicar);
                    $em->persist($arCuentaCobrar);

                    $saldo = $arCuentaCobrarAplicar->getVrSaldo() - $vrAplicar;
                    $saldoOperado = $saldo * $arCuentaCobrarAplicar->getOperacion();
                    $arCuentaCobrarAplicar->setVrSaldo($saldo);
                    $arCuentaCobrarAplicar->setVrSaldoOperado($saldoOperado);
                    $arCuentaCobrarAplicar->setVrAbono($arCuentaCobrarAplicar->getVrAbono() + $vrAplicar);
                    $em->persist($arCuentaCobrarAplicar);

                    $arAplicacion = new CarAplicacion();
                    $arAplicacion->setCuentaCobrarRel($arCuentaCobrar);
                    $arAplicacion->setCuentaCobrarAplicacionRel($arCuentaCobrarAplicar);
                    $arAplicacion->setVrAplicacion($vrAplicar);
                    $arAplicacion->setFecha(new \DateTime('now'));
                    $arAplicacion->setNumeroDocumento($arCuentaCobrar->getNumeroDocumento());
                    $arAplicacion->setNumeroDocumentoAplicacion($arCuentaCobrarAplicar->getNumeroDocumento());
                    $arAplicacion->setAutomatica(1);
                    $em->persist($arAplicacion);
                    $em->flush();
                }
            }
        }
    }

}