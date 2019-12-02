<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TtePrecio;
use App\Entity\Transporte\TtePrecioDetalle;
use App\Utilidades\Mensajes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class TtePrecioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TtePrecio::class);
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(TtePrecio::class, 'pr')
            ->select('pr.codigoPrecioPk')
            ->addSelect('pr.nombre')
            ->addSelect('pr.fechaVence')
            ->addSelect('pr.comentario')
            ->addSelect('pr.omitirDescuento')
            ->where('pr.codigoPrecioPk IS NOT NULL')
            ->orderBy('pr.codigoPrecioPk', 'DESC');
        if ($session->get('filtroTteNombrePrecio') != '') {
            $queryBuilder->andWhere("pr.nombre LIKE '%{$session->get('filtroTteNombrePrecio')}%' ");
        }

        return $queryBuilder;
    }

    public function camposPredeterminados()
    {
        $qb = $this->_em->createQueryBuilder()
            ->from('App:Transporte\TtePrecio', 'pr')
            ->select('pr.codigoPrecioPk AS ID')
            ->addSelect('pr.nombre AS NOMBRE')
            ->addSelect('pr.fechaVence AS FECHA_VENCE')
            ->addSelect('pr.comentario AS COMENTARIOS');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados as $codigo) {
                $arRegistro = $this->getEntityManager()->getRepository(TtePrecio::class)->find($codigo);
                if ($arRegistro) {
                    if (count($this->getEntityManager()->getRepository(TtePrecioDetalle::class)->findBy(['codigoPrecioFk' => $arRegistro->getCodigoPrecioPk()])) <= 0) {
                        $this->getEntityManager()->remove($arRegistro);
                    } else {
                        $respuesta = 'No se puede eliminar, el registro tiene detalles';
                    }
                }
                if ($respuesta != '') {
                    Mensajes::error($respuesta);
                } else {
                    $this->getEntityManager()->flush();
                }
            }
        }
    }

}