<?php

namespace App\Repository\Transporte;

use App\Entity\Transporte\TteGuiaCarga;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TteGuiaCargaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TteGuiaCarga::class);
    }

    /**
     * @param $arrSeleccionados
     * @throws \Doctrine\ORM\ORMException
     */
    public function eliminar($arrSeleccionados)
    {
        foreach ($arrSeleccionados as $arrSeleccionado) {
            $ar = $this->getEntityManager()->getRepository(TteGuiaCarga::class)->find($arrSeleccionado);
            if ($ar) {
                $this->getEntityManager()->remove($ar);
            }
        }
        $this->getEntityManager()->flush();
    }

    public function lista(){
        $session = new Session();
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from('App:Transporte\TteGuiaCarga','tgc')
            ->select('tgc.codigoGuiaCargaPk')
            ->addSelect('tgc.fechaRegistro')
            ->addSelect('tgc.numero')
            ->addSelect('tgc.cliente')
            ->addSelect('tgc.remitente')
            ->addSelect('tgc.relacionCliente')
            ->addSelect('tgc.documentoCliente')
            ->addSelect('tgc.nombreDestinatario')
            ->addSelect('tgc.direccionDestinatario')
            ->addSelect('tgc.telefonoDestinatario')
            ->addSelect('tgc.codigoCiudadOrigenFk')
            ->addSelect('tgc.codigoCiudadDestinoFk')
            ->addSelect('tgc.comentario')
            ->addSelect('tgc.vrDeclarado')
            ->where('tgc.codigoGuiaCargaPk <> 0');
        if($session->get('filtroTteGuiaCargaCliente') != ''){
            $queryBuilder->andWhere("tgc.cliente LIKE '%{$session->get('filtroTteGuiaCargaCliente')}%' ");
        }
        return $queryBuilder;
    }

    public function eliminarTodo(){
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->delete(TteGuiaCarga::class);
        try{
            $this->getEntityManager()->createQuery($queryBuilder)->execute();
        } catch (\Exception $exception){
        }
    }
}