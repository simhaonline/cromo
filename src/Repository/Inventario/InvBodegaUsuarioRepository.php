<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvBodegaUsuario;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvBodegaUsuarioRepository extends ServiceEntityRepository
{
    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvBodegaUsuario::class, 'bu')
            ->select('bu.codigoBodegaUsuarioPk')
            ->addSelect('bu.usuario')
            ->addSelect('b.nombre AS bodega')
            ->where('bu.codigoBodegaUsuarioPk <> 0')
            ->leftJoin('bu.bodegaRel', 'b')
            ->addOrderBy('bu.usuario', 'ASC');


        return $queryBuilder;
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvBodegaUsuario::class);
    }

    public function camposPredeterminados()
    {
        return $this->_em->createQueryBuilder()->from(InvBodegaUsuario::class, 'bd')
            ->select('bd.codigoBodegaUsuarioPk AS ID')
            ->addSelect('bd.codigoBodegaFk')
            ->addSelect('bd.usuario')
            ->where('bd.codigoBodegaUsuarioPk IS NOT NULL');
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvBodegaUsuario::class)->find($codigo);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                    $this->getEntityManager()->flush();
                }
            }
        }
    }
}