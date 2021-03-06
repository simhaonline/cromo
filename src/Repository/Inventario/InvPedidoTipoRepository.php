<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedidoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvPedidoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvPedidoTipo::class);
    }

//    public function lista(): array
//    {
//        $session = new Session();
//        $em = $this->getEntityManager();
//        $dql = 'SELECT pd.codigoPedidoTipoPk
//        FROM App\Entity\Inventario\InvPedidoTipo pt
//        WHERE pt.codigoPedidoTipoPk <> 0 ';
//        /*if($session->get('filtroTteCodigoGuiaTipo')) {
//            $dql .= " AND g.codigoGuiaTipoFk = '" . $session->get('filtroTteCodigoGuiaTipo') . "'";
//        }
//        if($session->get('filtroTteCodigoServicio')) {
//            $dql .= " AND g.codigoServicioFk = '" . $session->get('filtroTteCodigoServicio') . "'";
//        }
//        if($session->get('filtroTteDocumento') != "") {
//            $dql .= " AND g.documentoCliente LIKE '%" . $session->get('filtroTteDocumento') . "%'";
//        }
//        if($session->get('filtroTteNumeroGuia') != "") {
//            $dql .= " AND g.numero =" . $session->get('filtroTteNumeroGuia');
//        }*/
//        $dql .= " ORDER BY pt.codigoPedidoTipoPk";
//        $query = $em->createQuery($dql);
//        return $query->execute();
//    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvPedidoTipo','ioct');
        $qb
            ->select('ioct.codigoPedidoTipoPk AS ID')
            ->addSelect('ioct.nombre AS NOMBRE');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvPedidoTipo',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('pt')
                    ->orderBy('pt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvPedidoTipo')) {
            $array['data'] = $this->getEntityManager()->getReference(InvPedidoTipo::class, $session->get('filtroInvPedidoTipo'));
        }
        return $array;
    }


    public function  lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;


        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;


        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvPedidoTipo::class, 'pdt')
            ->select('pdt.codigoPedidoTipoPk')
            ->addSelect('pdt.nombre');


        if ($codigo) {
            $queryBuilder->andWhere("pdt.codigoPedidoTipoPk = '{$codigo}'");
        }

        if($nombre){
            $queryBuilder->andWhere("pdt.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('pdt.codigoPedidoTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvPedidoTipo::class)->find($codigo);
                    if ($arRegistro) {
                        $em->remove($arRegistro);
                    }
                }
                try {
                    $em->flush();
                } catch (\Exception $e) {
                    Mensajes::error('No se puede eliminar, el registro se encuentra en uso en el sistema');
                }
            }
        }else{
            Mensajes::error("No existen registros para eliminar");
        }
    }


}

