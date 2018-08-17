<?php

namespace App\Repository\Cartera;

use App\Entity\Cartera\CarCliente;
use App\Entity\Contabilidad\CtbTercero;
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
            ->addSelect('cc.nombreCorto')
            ->addSelect('cc.numeroIdentificacion')
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

    public function terceroContabilidad($codigo)
    {
        $em = $this->getEntityManager();
        $arTercero = null;
        $arCliente = $em->getRepository(CarCliente::class)->find($codigo);
        if($arCliente) {
            $arTercero = $em->getRepository(CtbTercero::class)->findOneBy(array('codigoIdentificacionFk' => $arCliente->getCodigoIdentificacionFk(), 'numeroIdentificacion' => $arCliente->getNumeroIdentificacion()));
            if(!$arTercero) {
                $arTercero = new CtbTercero();
                $arTercero->setIdentificacionRel($arCliente->getIdentificacionRel());
                $arTercero->setNumeroIdentificacion($arCliente->getNumeroIdentificacion());
                $arTercero->setNombreCorto($arCliente->getNombreCorto());
                $arTercero->setDireccion($arCliente->getDireccion());
                $arTercero->setTelefono($arTercero->getTelefono());
                //$arTercero->setEmail($arCliente->getCorreo());
                $em->persist($arTercero);
            }
        }

        return $arTercero;
    }

}