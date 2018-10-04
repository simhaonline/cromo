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
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()->from('App:Transporte\TteGuiaCarga','gc')
            ->select('gc.codigoGuiaCargaPk')
            ->addSelect('gc.fechaRegistro')
            ->addSelect('gc.numero')
            ->addSelect('gc.cliente')
            ->addSelect('gc.remitente')
            ->addSelect('gc.relacionCliente')
            ->addSelect('gc.documentoCliente')
            ->addSelect('gc.nombreDestinatario')
            ->addSelect('gc.direccionDestinatario')
            ->addSelect('gc.telefonoDestinatario')
            ->addSelect('gc.codigoCiudadOrigenFk')
            ->addSelect('gc.codigoCiudadDestinoFk')
            ->addSelect('gc.comentario')
            ->addSelect('gc.vrDeclarado')
            ->where('gc.codigoGuiaCargaPk <> 0')
        ->orderBy('gc.fechaRegistro', 'DESC');
        if($session->get('filtroTteGuiaCargaCliente') != ''){
            $queryBuilder->andWhere("gc.cliente LIKE '%{$session->get('filtroTteGuiaCargaCliente')}%' ");
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