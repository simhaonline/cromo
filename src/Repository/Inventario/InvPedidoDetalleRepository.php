<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedidoDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class InvPedidoDetalleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPedidoDetalle::class);
    }

    public function lista(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = 'SELECT pd.codigoPedidoDetallePk
        FROM App\Entity\Inventario\InvPedidoDetalle pd  
        WHERE pd.codigoPedidoDetallePk <> 0 ';
        /*if($session->get('filtroTteCodigoGuiaTipo')) {
            $dql .= " AND g.codigoGuiaTipoFk = '" . $session->get('filtroTteCodigoGuiaTipo') . "'";
        }
        if($session->get('filtroTteCodigoServicio')) {
            $dql .= " AND g.codigoServicioFk = '" . $session->get('filtroTteCodigoServicio') . "'";
        }
        if($session->get('filtroTteDocumento') != "") {
            $dql .= " AND g.documentoCliente LIKE '%" . $session->get('filtroTteDocumento') . "%'";
        }
        if($session->get('filtroTteNumeroGuia') != "") {
            $dql .= " AND g.numero =" . $session->get('filtroTteNumeroGuia');
        }*/
        $dql .= " ORDER BY pd.codigoPedidoDetallePk";
        $query = $em->createQuery($dql);
        return $query->execute();
    }

}