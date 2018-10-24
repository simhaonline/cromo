<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComCompra;
use App\Entity\Compra\ComCompraTipo;
use App\Entity\Compra\ComProveedor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('proveedorRel', EntityType::class, [
//                'required' => true,
//                'class' => ComProveedor::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('p')
//                        ->orderBy('p.nombreCorto', 'ASC');
//                },
//                'choice_label' => 'nombreCorto',
//                'label' => 'Proveedor:'
//            ])
            ->add('compraTipoRel', EntityType::class, [
                'class' => ComCompraTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Comprobante:'
            ])
            ->add('fecha' , DateType::class)
            ->add('soporte', TextType::class, ['required' => false])
            ->add('comentarios', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComCompra::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCompraPk",     "tipo":"pk",        "ayuda":"Codigo de compra",   "titulo":"ID"},
            {"campo":"numero",              "tipo":"entero",    "ayuda":"",                     "titulo":"NUMERO"},
            {"campo":"codigoCompraTipoFk", "tipo":"texto",     "ayuda":"",                     "titulo":"COMP"},
            {"campo":"fecha",               "tipo":"fecha",     "ayuda":"Fecha de registro",    "titulo":"FECHA"},
            {"campo":"estadoAutorizado",    "tipo":"bool",      "ayuda":"",                     "titulo":"AUT"},
            {"campo":"estadoAprobado",      "tipo":"bool",      "ayuda":"",                     "titulo":"APR"},
            {"campo":"estadoAnulado",       "tipo":"bool",      "ayuda":"",                     "titulo":"ANU"}                                
        ]';
        return $campos;
    }
}
