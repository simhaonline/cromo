<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class CarClienteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CarCliente::class);
    }

    public function camposPredeterminados(){
        $qb = $this-> _em->createQueryBuilder()
            ->from('App:Cartera\CarCliente','cl')
            ->select('cl.codigoClientePk AS ID')
            ->addSelect('cl.nombreCorto AS NOMBRE ');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista()
    {
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from(CarCliente::class, 'cc')
            ->select('cc.codigoClientePk')
            ->addSelect('cc.nombreCorto AS NOMBRE')
            ->addSelect('cc.digitoVerificacion')
            ->addSelect('cc.plazoPago AS PLAZO')
            ->where('cc.codigoClientePk <> 0')
            ->orderBy('cc.codigoClientePk', 'DESC');
        if ($session->get('filtroTteNombreCliente') != '') {
            $queryBuilder->andWhere("cc.nombreCorto LIKE '%{$session->get('filtroTteNombreCliente')}%' ");
        }
        if ($session->get('filtroNitCliente') != '') {
            $queryBuilder->andWhere("cc.numeroIdentificacion LIKE '%{$session->get('filtroTteNitCliente')}%' ");
        }
        if ($session->get('filtroTteCodigoCliente') != '') {
            $queryBuilder->andWhere("cc.codigoClientePk LIKE '%{$session->get('filtroTteCodigoCliente')}%' ");
        }

        return $queryBuilder;
    }

    public function eliminar($arrSeleccionados)
    {
        $respuesta = '';
        $em = $this->getEntityManager();
        if ($arrSeleccionados) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository(CarCliente::class)->find($codigo);
                if ($ar) {
                    $em->remove($ar);
                }
            }
            try {
                $em->flush();
            } catch (\Exception $exception) {
                $respuesta = 'No se puede eliminar, el registro esta siendo utilizado en el sistema';
            }
        }
        return $respuesta;
    }

}