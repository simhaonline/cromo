<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class TteFacturaTipoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteFacturaTipo::class);
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TteFacturaTipo', 'ft')
            ->select('ft.codigoFacturaTipoPk AS ID')
            ->addSelect('ft.nombre AS NOMBRE')
            ->addSelect('ft.prefijo AS PREFIJO')
            ->addSelect('ft.consecutivo AS CONSECUTIVO')
            ->addSelect('ft.codigoCuentaIngresoFleteFk AS CUENTA_INGRESO_FLETE')
            ->addSelect('ft.codigoCuentaIngresoManejoFk AS CUENTA_INGRESO_MANEJO')
            ->addSelect('ft.codigoCuentaClienteFk AS CUENTA_CLIENTE')
            ->addSelect('ft.naturalezaCuentaIngreso AS NATURALEZA_CUENTA_INGRESO')
            ->addSelect('ft.naturalezaCuentaCliente AS NATURALEZA_CUENTA_CLIENTE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function controlFactura($fecha)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaTipo::class, 'ft')
            ->select('ft.codigoFacturaTipoPk')
            ->addSelect('ft.nombre');
        $arFacturaTipos = $queryBuilder->getQuery()->execute();
        $pos = 0;
        foreach ($arFacturaTipos as $arFacturaTipo) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFactura::class, 'f')
                ->select('MIN(f.numero) as desde')
                ->addSelect('MAX(f.numero) as hasta')
                ->addSelect('SUM(f.vrTotal) AS vrTotal')
                ->addSelect('COUNT(f.codigoFacturaPk) AS numeroFacturas')
                ->where("f.codigoFacturaTipoFk = '" . $arFacturaTipo['codigoFacturaTipoPk'] . "'")
                ->andWhere("f.fecha >= '$fecha 00:00:00' AND f.fecha <= '$fecha 23:59:59'")
                ->andWhere("f.estadoAprobado = 1");
            $arFactura = $queryBuilder->getQuery()->getSingleResult();
            if ($arFactura['desde']) {
                $arFacturaTipos[$pos]['desde'] = $arFactura['desde'];
                $arFacturaTipos[$pos]['hasta'] = $arFactura['hasta'];
                $arFacturaTipos[$pos]['vrTotal'] = $arFactura['vrTotal'];
                $arFacturaTipos[$pos]['numeroFacturas'] = $arFactura['numeroFacturas'];
            } else {
                $arFacturaTipos[$pos]['desde'] = "Sin registros";
                $arFacturaTipos[$pos]['hasta'] = "Sin registros";
                $arFacturaTipos[$pos]['vrTotal'] = 0;
                $arFacturaTipos[$pos]['numeroFacturas'] = 0;
            }
            $pos++;
        }

        return $arFacturaTipos;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => TteFacturaTipo::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ft')
                    ->orderBy('ft.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        ];
        if ($session->get('filtroTteFacturaCodigoFacturaTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(TteFacturaTipo::class, $session->get('filtroTteFacturaCodigoFacturaTipo'));
        }
        return $array;
    }

}