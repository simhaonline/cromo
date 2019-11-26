<?php

namespace App\Controller\Inventario\Administracion\Inventario\BodegaUsuario;


use App\Controller\Estructura\FuncionesController;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvBodegaUsuario;
use App\Entity\Seguridad\Usuario;
use App\Form\Type\RecursoHumano\BodegaUsuarioType;
use App\General\General;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class BodegaUsuarioController extends AbstractController
{
    /**
     * @Route("/inventario/administracion/inventario/bodegausuario/lista", name="inventario_administracion_inventario_bodegausuario_lista")
     */
    public function lista(Request $request, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
        $form = $this->createFormBuilder()
            ->add('btnEliminar', SubmitType::class, array('label' => 'Eliminar'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository(InvBodegaUsuario::class)->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('inventario_administracion_inventario_bodegausuario_lista'));
            }
        }

        $query = $this->getDoctrine()->getRepository(InvBodegaUsuario::class)->lista();
        $arBodegaUsuario = $paginator->paginate($query, $request->query->getInt('page', 1), 50);
        return $this->render('inventario/administracion/inventario/bodegaUsuario/lista.html.twig', [
            'arBodegaUsuario' => $arBodegaUsuario,
            'form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/inventario/bodegaUsuario/nuevo/{id}",name="inventario_administracion_inventario_bodegausuario_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arBodegaUsuario = new InvBodegaUsuario();
        if ($id != 0) {
            $arBodegaUsuario = $em->getRepository(InvBodegaUsuario::class)->find($id);
        }
        $arrUsuarioRel = ['class' => Usuario::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('er')
                    ->orderBy('er.username');
            }, 'required' => true,
            'choice_label' => 'username'];
        $arrBodegaRel = ['class' => InvBodega::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('er')
                    ->orderBy('er.nombre');
            }, 'required' => true,
            'choice_label' => 'nombre'];
        if ($id != 0) {
            $arUser = $em->getRepository(Usuario::class)->findOneBy(['username' => $arBodegaUsuario->getUsuario()]);
            $arrUsuarioRel['data'] = $em->getReference(Usuario::class, $arUser->getId());
        }
        if ($id != 0) {
            $arrBodegaRel['data'] = $em->getReference(InvBodega::class, $arBodegaUsuario->getCodigoBodegaFk());
        }
        $form = $this->createFormBuilder()
            ->add('usuarioRel', EntityType::class, $arrUsuarioRel)
            ->add('bodegaRel', EntityType::class, $arrBodegaRel)
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arBodegaUsuarioBuscar = $em->getRepository(InvBodegaUsuario::class)->findOneBy(['usuario' => $form->get('usuarioRel')->getData()->getUsername(), 'codigoBodegaFk' => $form->get('bodegaRel')->getData()->getCodigoBodegaPk()]);
                if (!$arBodegaUsuarioBuscar || $arBodegaUsuario->getCodigoBodegaUsuarioPk() == $arBodegaUsuarioBuscar->getCodigoBodegaUsuarioPk()) {
                    $arBodegaUsuario->setUsuario($form->get('usuarioRel')->getData()->getUsername());
                    $arBodegaUsuario->setBodegaRel($form->get('bodegaRel')->getData());
                    $em->persist($arBodegaUsuario);
                    $em->flush();
                    return $this->redirect($this->generateUrl('inventario_administracion_inventario_bodegausuario_lista'));
                } else {
                    Mensajes::error('El usuario seleccionado ya tiene acceso a esta bodega');
                }
            }
        }
        return $this->render('inventario/administracion/inventario/bodegaUsuario/nuevo.html.twig', [
            'form' => $form->createView()
        ]);
    }


}