<?php

namespace App\Repository\General;

use App\Entity\General\GenConfiguracionEntidad;
use App\Entity\General\GenCubo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\Form;

class GenCuboRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenCubo::class);
    }

    /**
     * @param $form
     * @param $arRegistro
     * @param $entidadCubo
     * @return mixed
     */
    public function guardar($form, $arRegistro, $entidadCubo)
    {
        $em = $this->getEntityManager();
        $arRegistro->setNombre($form->get('nombre')->getData());
        $arrRegistros = [
            "columnas" => $form->get('columnas')->getData(),
            'orden' => $form->get('ordenar')->getData(),
            'tipoOrden' => $form->get('ordenTipo')->getData(),
            'condicion' => $form->get('condicion')->getData(),
            'operadorCondicion' => $form->get('operadorCondicion')->getData(),
            'valorCondicion' => $form->get('valorCondicion')->getData()
        ];
        $arRegistro->setJsonCubo(json_encode($arrRegistros));
        $arConfiguracionEntidadCubo = $em->getRepository("App:General\GenConfiguracionEntidad")->find($entidadCubo);
        $qb = $em->createQueryBuilder()->from($arConfiguracionEntidadCubo->getRutaEntidad(), "tbl");
        foreach ($arrRegistros['columnas'] as $columna) {
            if (strpos($columna, 'Rel')) {
                $relacion = lcfirst($columna);
                $qb->leftJoin("tbl.{$relacion}", "$relacion")
                    ->addSelect("{$relacion}.nombre AS {$columna}");
            } else {
                $qb->addSelect("tbl.$columna");
            }
        }
        foreach ($arrRegistros['orden'] as $orden) {
            $qb->addOrderBy("tbl.$orden", $arrRegistros['tipoOrden']);
        }
        if ($arrRegistros['condicion']) {
            $qb->andWhere("tbl.{$arrRegistros['condicion']} {$arrRegistros['operadorCondicion']} '{$arrRegistros['valorCondicion']}'");
        }

        $sqlCubo = $qb->getDQL();

        $arRegistro->setSqlCubo($sqlCubo);

        return $arRegistro;
    }

}