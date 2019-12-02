<?php

namespace App\Repository\Inventario;


use App\Entity\Financiero\FinContacto;
use App\Entity\Inventario\InvContacto;
use App\Entity\Inventario\InvCotizacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvContactoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvContacto::class);
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoTercero = null;
        $tercero = null;

        if ($filtros) {
            $codigoTercero = $filtros['codigoTercero'] ?? null;
            $tercero = $filtros['tercero'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvContacto::class, 'ct')
            ->select('ct.codigoContactoPk')
            ->addSelect('ct.nombreCorto')
            ->addSelect('ct.numeroIdentificacion')
            ->addSelect('ct.cargo')
            ->addSelect('ct.area')
            ->addSelect('ct.telefono')
            ->addSelect('ct.celular')
            ->addSelect('t.nombreCorto AS tercero')
            ->leftJoin('ct.terceroRel', 't')
            ->where('ct.codigoContactoPk <> 0');
        if ($codigoTercero) {
            $queryBuilder->andWhere("ct.codigoContactoPk = '{$codigoTercero}'");
        }
        if ($tercero) {
            $queryBuilder->andWhere("t.nombreCorto like '%{$tercero}%'");
        }

        $queryBuilder->addOrderBy('ct.codigoContactoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(InvContacto::class)->find($arrSeleccionado);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                }
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
    }

    public function listaTercero($codigoTercero)
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvContacto::class,'c')
            ->select('c.codigoContactoPk')
            ->addSelect('c.nombreCorto')
            ->addSelect('c.numeroIdentificacion')
            ->addSelect('c.telefono')
            ->addSelect('c.celular')
            ->addSelect('t.nombreCorto AS terceroNombreCorto')
            ->where("c.codigoTerceroFk = $codigoTercero")
            ->join('c.terceroRel', 't');
        if($session->get('filtroInvBuscarContactoNombre')){
            $queryBuilder->andWhere("c.nombreCorto LIKE '%{$session->get('filtroInvBuscarContactoNombre')}%'");
            $session->set('filtroInvBuscarContactoNombre',null);
        }
        return $queryBuilder;
    }
}
