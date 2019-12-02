<?php
namespace App\Repository\Transporte;
use App\Entity\Transporte\TteClienteCondicion;
use App\Entity\Transporte\TteCondicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
class TteCondicionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TteCondicion::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Transporte\TteCondicion','cc')
            ->select('cc.codigoCondicionPk AS ID')
            ->addSelect('cc.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TteCondicion::class, 'cc')
            ->select('cc.codigoCondicionPk')
            ->addSelect('cc.nombre')
            ->addSelect('cc.porcentajeManejo')
            ->addSelect('cc.manejoMinimoUnidad')
            ->addSelect('cc.manejoMinimoDespacho')
            ->addSelect('cc.precioPeso')
            ->addSelect('cc.precioUnidad')
            ->addSelect('cc.precioAdicional')
            ->addSelect('cc.descuentoPeso')
            ->addSelect('cc.descuentoUnidad')
            ->addSelect('cc.permiteRecaudo')
            ->where('cc.codigoCondicionPk IS NOT NULL')
            ->orderBy('cc.codigoCondicionPk', 'ASC');
        if ($session->get('filtroNombreCondicion') != '') {
            $queryBuilder->andWhere("cc.nombre LIKE '%{$session->get('filtroNombreCondicion')}%' ");
        }
        return $queryBuilder;
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo(){
        $session = new Session();
        $array = [
            'class' => TteCondicion::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroTteCondicion')) {
            $array['data'] = $this->getEntityManager()->getReference(TteCondicion::class, $session->get('filtroTteCondicion'));
        }
        return $array;
    }

    public function tipoLiquidacion($arCondicion) {
        if ($arCondicion->getPrecioPeso()) {
            $tipoLiquidacion = "K";
        } else if ($arCondicion->getPrecioUnidad()) {
            $tipoLiquidacion = "U";
        } else if ($arCondicion->getPrecioAdicional()) {
            $tipoLiquidacion = "A";
        } else{
            $tipoLiquidacion = "K";
        }
        return $tipoLiquidacion;
    }

    public function apiWindowsBuscar($raw) {
        $em = $this->getEntityManager();
        $nombre = $raw['nombre']?? null;
        $cliente = $raw['cliente']?? null;
        if($cliente) {
            $queryBuilder = $em->createQueryBuilder()->from(TteClienteCondicion::class, 'cc')
                ->select('cc.codigoCondicionFk')
                ->addSelect('c.nombre')
                ->addSelect('cc.codigoClienteCondicionPk')
                ->leftJoin('cc.condicionRel', 'c')
                ->where('cc.codigoClienteFk=' . $cliente)
                ->setMaxResults(10);
            if($nombre) {
                $queryBuilder->andWhere("c.nombre LIKE '%${nombre}%'");
            }
            $arCondiciones = $queryBuilder->getQuery()->getResult();
            return $arCondiciones;
        } else {
            return [
                "error" => "Faltan datos para la api"
            ];
        }
    }

    public function apiWindowsDetalle($raw) {
        $em = $this->getEntityManager();
        $codigo = $raw['codigo']?? null;
        if($codigo) {
            $queryBuilder = $em->createQueryBuilder()->from(TteCondicion::class, 'c')
                ->select('c.codigoCondicionPk')
                ->addSelect('c.nombre')
                ->addSelect('c.porcentajeManejo')
                ->addSelect('c.manejoMinimoUnidad')
                ->addSelect('c.manejoMinimoDespacho')
                ->addSelect('c.pesoMinimo')
                ->addSelect('c.descuentoPeso')
                ->addSelect('c.codigoPrecioFk')
                ->addSelect('c.precioGeneral')
                ->addSelect('c.precioPeso')
                ->addSelect('c.precioUnidad')
                ->addSelect('c.precioAdicional');
            if($codigo) {
                $queryBuilder->where("c.codigoCondicionPk=" . $codigo);
            }
            $arCondiciones = $queryBuilder->getQuery()->getResult();
            if($arCondiciones) {
                return $arCondiciones[0];
            } else {
                return [
                    "error" => "No se encontraron resultados"
                ];
            }
        } else {
            return [
                "error" => "Faltan datos para la api"
            ];
        }
    }

}