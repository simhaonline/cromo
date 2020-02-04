<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvPrecio;
use App\Entity\Inventario\InvSucursal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvSucursalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvSucursal::class);
    }
// NOTA: ANTIGUO METODO LISTA
//    /**
//     * @return \Doctrine\ORM\QueryBuilder
//     */
//    public function lista()
//    {
//        $session = new Session();
//        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSucursal::class,'s')
//            ->select('s.codigoSucursalPk')
//            ->leftJoin('s.ciudadRel','c')
//            ->addSelect('s.direccion')
//            ->addSelect('s.contacto')
//            ->addSelect('c.nombre AS ciudad')
//            ->addSelect('s.nombre')
//            ->where("s.codigoTerceroFk ={$session->get('filtroInvBuscarSucursalCodigoTercero')}");
//        if($session->get('filtroInvBuscarSucursalDireccion')){
//            $queryBuilder->andWhere("s.direccion LIKE '%{$session->get('filtroInvBuscarSucursalDireccion')}%'");
//            $session->set('filtroInvBuscarSucursalDireccion',null);
//        }
//        if($session->get('filtroInvBuscarSucursalContacto')){
//            $queryBuilder->andWhere("s.contacto LIKE '%{$session->get('filtroInvBuscarSucursalContacto')}%'");
//            $session->set('filtroInvBuscarSucursalContacto',null);
//        }
//        return $queryBuilder;
//    }

    public function listaSucursal($codigoTercero ){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSucursal::class,'s')
            ->select('s.codigoSucursalPk')
            ->leftJoin('s.ciudadRel','c')
            ->addSelect('s.direccion')
            ->addSelect('s.contacto')
            ->addSelect('c.nombre AS ciudad')
            ->addSelect('s.nombre')
            ->where("s.codigoTerceroFk ={$codigoTercero}");
        return $queryBuilder;
    }

    public function listaTercero($codigoTercero)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSucursal::class,'s')
            ->select('s.codigoSucursalPk')
            ->leftJoin('s.ciudadRel','c')
            ->addSelect('s.direccion')
            ->addSelect('s.contacto')
            ->addSelect('c.nombre AS ciudad')
            ->addSelect('s.nombre')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where("s.codigoTerceroFk = $codigoTercero")
            ->join('s.terceroRel', 't');
        if($session->get('filtroInvBuscarSucursalDireccion')){
            $queryBuilder->andWhere("s.direccion LIKE '%{$session->get('filtroInvBuscarSucursalDireccion')}%'");
            $session->set('filtroInvBuscarSucursalDireccion',null);
        }
        if($session->get('filtroInvBuscarSucursalContacto')){
            $queryBuilder->andWhere("s.contacto LIKE '%{$session->get('filtroInvBuscarSucursalContacto')}%'");
            $session->set('filtroInvBuscarSucursalContacto',null);
        }
        return $queryBuilder;
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from(InvSucursal::class,'s')
            ->select('s.codigoSucursalPk AS ID')
            ->addSelect('s.nombre')
            ->addSelect('s.contacto')
            ->addSelect('s.direccion')
            ->addSelect('t.nombreCorto')
            ->leftJoin('s.terceroRel','t');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }


    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigo = null;
        $nombre = null;
        $codigoTercero = null;


        if ($filtros) {
            $codigo = $filtros['codigo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
            $codigoTercero = $filtros['codigoTercero'] ?? null;

        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvSucursal::class, 's')
            ->select('s.codigoSucursalPk')
            ->addSelect('s.direccion')
            ->addSelect('s.contacto')
            ->addSelect('c.nombre AS ciudad')
            ->addSelect('s.nombre')
            ->leftJoin('s.ciudadRel','c');
        if ($codigo) {
            $queryBuilder->andWhere("s.codigoSucursalPk = '{$codigo}'");
        }  if ($codigoTercero) {
            $queryBuilder->andWhere("s.codigoTerceroFk = '{$codigoTercero}'");
        }

        if ($nombre) {
            $queryBuilder->andWhere("s.nombre LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('s.codigoSucursalPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();

    }

    public function eliminar($arrDetallesSeleccionados)
    {
        $em = $this->getEntityManager();
        if ($arrDetallesSeleccionados) {
            if (count($arrDetallesSeleccionados)) {
                foreach ($arrDetallesSeleccionados as $codigo) {
                    $arRegistro = $em->getRepository(InvSucursal::class)->find($codigo);
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
        } else {
            Mensajes::error("No existen registros para eliminar");
        }
    }

}


