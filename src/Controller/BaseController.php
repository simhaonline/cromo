<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
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
        $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder()->from($nombreRepositorio,'e')
            ->select('e.' . $campos[0]->campo);
        foreach ($campos as $campo) {
            if($campo->tipo != "pk") {
                $queryBuilder->addSelect('e.' . $campo->campo);
            }
        }
        $paginator = $this->get('knp_paginator');
        $query = $queryBuilder->getQuery();
        return [
            'ruta' => strtolower($this->modulo) . "_movimiento_" . strtolower($this->grupo) . "_" . strtolower($this->nombre),
            'arrCampos' => $campos,
            'arDatos' => $paginator->paginate($query, $this->request->query->getInt('page', 1), 30)
        ];
    }

    protected function botoneraLista(){
        return $form = $this->createFormBuilder()
            ->add('btnEliminar',SubmitType::class,['label' => 'Eliminar','attr' => ['class' => 'btn-sm btn btn-danger']])
            ->add('btnExcel',SubmitType::class,['label' => 'Excel','attr' => ['class' => 'btn-sm btn btn-deafult']])
            ->getForm();
    }
}