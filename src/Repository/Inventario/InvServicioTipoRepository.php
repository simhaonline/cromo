<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvServicioTipo;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method InvServicioTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvServicioTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvServicioTipo[]    findAll()
 * @method InvServicioTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvServicioTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvServicioTipo::class);
    }


    public function lista($raw){

        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;
        $codigoServicioTipo = null;
        $nombre = null;

        if ($filtros) {
            $codigoServicioTipo = $filtros['codigoServicioTipo'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(InvServicioTipo::class, 'st')
            ->select('st.codigoServicioTipoPk')
            ->addSelect('st.nombre');

        if ($codigoServicioTipo) {
            $queryBuilder->andWhere("st.codigoServicioTipoPk = '{$codigoServicioTipo}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("st.nombre  LIKE '%{$nombre}%'");
        }

        $queryBuilder->addOrderBy('st.codigoServicioTipoPk', 'DESC');
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(InvServicioTipo::class)->find($codigo);
                if ($arRegistro) {
                    $arServicio=$this->getEntityManager()->getRepository('App:Inventario\InvServicio')->findBy(['codigoServicioTipoFk'=>$codigo]);
                    if($arServicio){
                        $respuesta='No se puede eliminar el registro, esta siendo utilizado en uno o mas servicios';
                    }
                    if ($respuesta != '') {
                        Mensajes::error($respuesta);
                    } else {
                        $this->getEntityManager()->remove($arRegistro);
                        $this->getEntityManager()->flush();
                    }
                }
            }
        }
    }


}
