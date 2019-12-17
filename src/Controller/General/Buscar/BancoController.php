<?php


namespace App\Controller\General\Buscar;


use App\Entity\Financiero\FinTercero;
use App\Entity\General\GenBanco;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BancoController extends AbstractController
{
    /**
     * @Route("/general/buscar/banco/{campoCodigo}", name="general_buscar_banco")
     */
    public function lista(Request $request, PaginatorInterface $paginator,  $campoCodigo)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('codigoBancoPk', TextType::class, array('required' => false))
            ->add('nombre', TextType::class, array('required' => false))
            ->add('btnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);
        $raw = [];
        if ($form->isSubmitted()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $raw['filtros'] =[
                    'codigoBanco'=>$form->get('codigoBancoPk')->getData(),
                    'nombre'=>$form->get('nombre')->getData()
                ];
            }
        }
        $arBancos = $paginator->paginate($em->getRepository(GenBanco::class)->lista($raw), $request->query->getInt('page', 1),20);
        return $this->render('general/buscar/banco.html.twig', array(
            'arBancos' => $arBancos,
            'campoCodigo' => $campoCodigo,
            'form' => $form->createView()
        ));
    }
}