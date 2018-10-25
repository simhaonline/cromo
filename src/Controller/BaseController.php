<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use function PHPSTORM_META\elementType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseController extends Controller
{
    protected $request = null;

    protected function getDatosLista()
    {
        $nombreRepositorio = "App:{$this->modulo}\\{$this->claseNombre}";
        $namespaceType = "\\App\\Form\\Type\\{$this->modulo}\\{$this->nombre}Type";
        $campos = $namespaceType::getEstructuraPropiedadesLista();
        $campos = json_decode($campos);
        $arrRelaciones = [];
        /** @var  $queryBuilder QueryBuilder */
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()->from($nombreRepositorio, 'e')
            ->select('e.' . $campos[0]->campo);
        foreach ($campos as $campo) {
            if ($campo->tipo != "pk" && $campo->tipo != 'rel') {
                $queryBuilder->addSelect('e.' . $campo->campo);
            } elseif ($campo->tipo == 'rel') {
                $arrRel = explode('.', $campo->campo);
                if (!$this->validarRelacion($arrRelaciones, $arrRel[0])) {
                    $arrRelaciones[] = $arrRel[0];
                    $queryBuilder->leftJoin('e.' . $arrRel[0], $arrRel[0])
                        ->addSelect($arrRel[0] . '.' . $arrRel[1]);
                } else {
                    $queryBuilder->addSelect($arrRel[0] . '.' . $arrRel[1]);
                }
            }
        }
        $paginator = $this->get('knp_paginator');
        $query = $queryBuilder->getQuery();
        return [
            'ruta' => strtolower($this->modulo) . "_" . strtolower($this->funcion) . "_" . strtolower($this->grupo) . "_" . strtolower($this->nombre),
            'arrCampos' => $campos,
            'arDatos' => $paginator->paginate($query, $this->request->query->getInt('page', 1), 30)
        ];
    }

    protected function botoneraLista()
    {
        return $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, ['label' => 'Eliminar', 'attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel', SubmitType::class, ['label' => 'Excel', 'attr' => ['class' => 'btn-sm btn btn-deafult']])
            ->getForm();
    }

    /**
     * @param $arrRelaciones
     * @param $relacion
     * @return bool
     */
    private function validarRelacion($arrRelaciones, $relacion)
    {
        $relExiste = false;
        foreach ($arrRelaciones as $arrRelacion) {
            if ($arrRelacion == $relacion) {
                $relExiste = true;
                break;
            }
        }
        if ($relExiste) {
            return true;
        } else {
            return false;
        }
    }
}