<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteFactura;
use App\Entity\Transporte\TteFacturaTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class TteFacturaTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
                ->addSelect('SUM(f.vrTotalOperado) AS vrTotalOperado')
                ->addSelect('COUNT(f.codigoFacturaPk) AS numeroFacturas')
                ->where("f.codigoFacturaTipoFk = '" . $arFacturaTipo['codigoFacturaTipoPk'] . "'")
                ->andWhere("f.fecha >= '$fecha 00:00:00' AND f.fecha <= '$fecha 23:59:59'")
                ->andWhere("f.estadoAprobado = 1");
            $arFactura = $queryBuilder->getQuery()->getSingleResult();
            if ($arFactura['desde']) {
                $arFacturaTipos[$pos]['desde'] = $arFactura['desde'];
                $arFacturaTipos[$pos]['hasta'] = $arFactura['hasta'];
                $arFacturaTipos[$pos]['vrTotalOperado'] = $arFactura['vrTotalOperado'];
                $arFacturaTipos[$pos]['numeroFacturas'] = $arFactura['numeroFacturas'];
            } else {
                $arFacturaTipos[$pos]['desde'] = "Sin registros";
                $arFacturaTipos[$pos]['hasta'] = "Sin registros";
                $arFacturaTipos[$pos]['vrTotalOperado'] = 0;
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

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;

        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }


        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteFacturaTipo::class, 'ft')
            ->select('ft.codigoFacturaTipoPk')
            ->addSelect('ft.nombre')
            ->addSelect('ft.consecutivo')
            ->addSelect('ft.resolucionFacturacion')
            ->addSelect('ft.prefijo')
            ->addSelect('ft.guiaFactura')
            ->addSelect('ft.codigoFacturaClaseFk')
            ->addSelect('ft.codigoCuentaCobrarTipoFk')
            ->addSelect('ft.codigoCuentaIngresoFleteFk')
            ->addSelect('ft.codigoCuentaIngresoInicialFijoManejoFk')
            ->addSelect('ft.codigoCuentaIngresoInicialFijoManejoFk')
            ->addSelect('ft.codigoCuentaClienteFk')
            ->addSelect('ft.guiaFactura')
            ->addSelect('ft.codigoComprobanteFk');

        if ($codigo) {
            $queryBuilder->andWhere("ft.codigoFacturaTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("ft.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('ft.codigoFacturaTipoPk   ', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $em->getRepository(TteFacturaTipo::class)->find($codigo);
                if ($arRegistro) {
                    $em->remove($arRegistro);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $e) {
                Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }

}