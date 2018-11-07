<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvImportacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method InvImportacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvImportacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvImportacion[]    findAll()
 * @method InvImportacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvImportacionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvImportacion::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvImportacion::class,'i')
            ->select('i.codigoImportacionPk')
            ->addSelect('i.numero')
            ->addSelect('i.fecha')
            ->addSelect('it.nombre')
            ->addSelect('i.estadoAutorizado')
            ->addSelect('i.estadoAprobado')
            ->addSelect('i.estadoAnulado')
            ->addSelect('i.vrSubtotal')
            ->addSelect('i.vrIva')
            ->addSelect('i.vrNeto')
            ->addSelect('i.vrTotal')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->leftJoin('i.terceroRel', 't')
            ->leftJoin('i.importacionTipoRel', 'it')
            ->where('i.codigoImportacionPk <> 0')
            ->orderBy('i.codigoImportacionPk','DESC');
        if($session->get('filtroInvNumeroImportacion')) {
            $queryBuilder->andWhere("i.numero = {$session->get('filtroInvNumeroImportacion')}");
        }
        if($session->get('filtroInvImportacionTipo')) {
            $queryBuilder->andWhere("i.codigoPedidoTipoFk = '{$session->get('filtroInvImportacionTipo')}'");
        }
        if($session->get('filtroInvCodigoTercero')){
            $queryBuilder->andWhere("i.codigoTerceroFk = {$session->get('filtroInvCodigoTercero')}");
        }
        return $queryBuilder;

    }
}
