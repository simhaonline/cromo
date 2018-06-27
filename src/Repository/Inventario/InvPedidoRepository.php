<?php

namespace App\Repository\Inventario;


use App\Entity\Inventario\InvPedido;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
class InvPedidoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvPedido::class);
    }

    public function lista(): array
    {
        $session = new Session();
        $em = $this->getEntityManager();
        $dql = 'SELECT p.codigoPedidoPk,
        p.fecha,
        p.vrSubtotal,
        p.vrIva,
        p.vrNeto,
        p.vrTotal,
        p.estadoAutorizado,
        p.estadoAprobado,
        p.estadoAnulado
        FROM App\Entity\Inventario\InvPedido p  
        WHERE p.codigoPedidoPk <> 0 ';
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
        $dql .= " ORDER BY p.codigoPedidoPk";
        $query = $em->createQuery($dql);
        return $query->execute();
    }

}