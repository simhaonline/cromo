<?php

namespace App\Controller\Inventario\Administracion\Inventario\BodegaUsuario;


use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvBodegaUsuario;
use App\Entity\Seguridad\Usuario;
use App\Form\Type\RecursoHumano\BodegaUsuarioType;
use App\Utilidades\Mensajes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BodegaUsuarioController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/inventario/administracion/inventario/bodegaUsuario/nuevo/{id}",name="inventario_admin_inventario_bodegaUsuario_nuevo")
     */
    public function nuevo(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $arBodegaUsuario = new InvBodegaUsuario();
        if($id != 0){
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
        if($id != 0){
            $arUser = $em->getRepository(Usuario::class)->findOneBy(['username' => $arBodegaUsuario->getUsuario()]);
            $arrUsuarioRel['data'] = $em->getReference(Usuario::class, $arUser->getId());
        }
        if($id != 0){
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
                    return $this->redirect($this->generateUrl('admin_detalle',['modulo' => 'inventario','entidad' => 'bodegaUsuario','id' => $arBodegaUsuario->getCodigoBodegaUsuarioPk()]));
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