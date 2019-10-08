<?php

namespace App\Controller\Inventario\Informe\Inventario\Item;

use App\Controller\Estructura\ControllerListenerGeneral;
use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvItem;
use App\Entity\Inventario\InvLote;
use App\Entity\Inventario\InvMovimientoDetalle;
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

class StockBajoController extends AbstractController
{

    protected $proceso = "0007";

    /**
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @Route("/inventario/informe/inventario/item/stockBajo", name="inventario_informe_inventario_item_stockbajo")
     */
    public function lista(Request $request, PaginatorInterface $paginator )
    {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()
            ->add('txtCodigoItem', TextType::class, array('data' => $session->get('filtroInvInformeStockBajoItemCodigo'), 'required' => false))
            ->add('txtNombreItem', TextType::class, array('data' => $session->get('filtroInvInformeStockBajoItemNombre'), 'required' => false, 'attr' => ['readonly' => 'readonly']))
            ->add('btnExcel', SubmitType::class, array('label' => 'Excel'))
            ->add('btnFiltrar', SubmitType::class, ['label' => 'Filtrar', 'attr' => ['class' => 'btn btn-sm btn-default']])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('btnFiltrar')->isClicked()) {
                $session->set('filtroInvInformeStockBajoItemCodigo', $form->get('txtCodigoItem')->getData());
            }
            if ($form->get('btnExcel')->isClicked()) {
                General::get()->setExportar($em->getRepository(InvItem::class)->stockBajo()->getQuery()->getResult(), "Stock bajo");
            }
        }
        $arItems = $paginator->paginate($em->getRepository(InvItem::class)->stockBajo(), $request->query->getInt('page', 1), 100);
        return $this->render('inventario/informe/inventario/item/stockBajo.html.twig', [
            'arItems' => $arItems,
            'form' => $form->createView()
        ]);
    }

}

