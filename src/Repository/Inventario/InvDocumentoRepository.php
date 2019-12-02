<?php

namespace App\Repository\Inventario;

use App\Entity\Inventario\InvDocumento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

class InvDocumentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvDocumento::class);
    }

    public function camposPredeterminados(){
        $qb = $this->_em->createQueryBuilder()->from('App:Inventario\InvDocumento','id')
            ->select('id.codigoDocumentoPk as ID')
            ->addSelect('id.abreviatura')
            ->addSelect('id.nombre')
            ->addSelect('id.consecutivo');
        $query = $this->_em->createQuery($qb->getDQL());
        return $query->execute();
    }

    public function lista($raw)
    {
        $limiteRegistros = $raw['limiteRegistros'] ?? 100;
        $filtros = $raw['filtros'] ?? null;

        $codigoDocumento = null;
        $nombre = null;
        if ($filtros) {
            $codigoDocumento = $filtros['codigoDocumento'] ?? null;
            $nombre = $filtros['nombre'] ?? null;
        }
        $queryBuilder = $this->_em->createQueryBuilder()->from(InvDocumento::class, 'd')
            ->select('d.codigoDocumentoPk')
            ->addSelect('d.abreviatura')
            ->addSelect('d.nombre')
            ->addSelect('d.consecutivo')
            ->addSelect('d.operacionComercial')
            ->addOrderBy('d.codigoDocumentoPk', 'ASC');

        if ($codigoDocumento) {
            $queryBuilder->andWhere("d.codigoDocumentoPk = '{$codigoDocumento}'");
        }
        if ($nombre) {
            $queryBuilder->andWhere("d.nombre like '%{$nombre}'");
        }
        $queryBuilder->setMaxResults($limiteRegistros);
        return $queryBuilder->getQuery()->getResult();
    }

    public function eliminar($arrSeleccionados)
    {
        try{
            foreach ($arrSeleccionados as $arrSeleccionado) {
                $arRegistro = $this->getEntityManager()->getRepository(InvDocumento::class)->find($arrSeleccionado);
                if ($arRegistro) {
                    $this->getEntityManager()->remove($arRegistro);
                }
            }
            $this->getEntityManager()->flush();
        } catch (\Exception $ex) {
            Mensajes::error("El registro tiene registros relacionados");
        }
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     */
    public function llenarCombo()
    {
        $session = new Session();
        $array = [
            'class' => 'App:Inventario\InvDocumento',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('dt')
                    ->orderBy('dt.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""];
        if ($session->get('filtroInvCodigoDocumento')) {
            $array['data'] = $this->getEntityManager()->getReference(InvDocumento::class, $session->get('filtroInvCodigoDocumento'));
        }
        return $array;
    }
}