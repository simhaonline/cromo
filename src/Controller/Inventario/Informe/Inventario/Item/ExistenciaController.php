<?php

namespace App\Controller\Inventario\Informe\Inventario\Item;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Controller\MaestroController;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Formato\Inventario\ExistenciaLote;
use App\General\General;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExistenciaController extends MaestroController
{

    public $tipo = "informe";
    public $proceso = "invi0001";




    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/item/existencia", name="inventario_informe_inventario_item_existencia")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, array('data' => $session->get('filtroInvInformeItemCodigo'), 'required' => false))
            ->add('txtNombreItem', TextType::class, array('data' => $session->get('filtroInvInformeItemNombre'), 'required' => false , 'attr' => ['readonly' => 'readonly']))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvInformeItemCodigo', $form->get('txtCodigoItem')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvItem::class)->existencia()->getQuery()->getResult(), "Existencia");
            }
        }
        $arItemes = $paginator->paginate($em->getRepository(InvItem::class)->existencia(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/informe/inventario/item/existencia.html.twig', [
            'arItemes' => $arItemes,
            'form' => $form->createView()
        ]);
    }

}

