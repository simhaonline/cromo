<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurItem;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => true])
            ->add('impuestoRetencionRel', EntityType::class, [
                'class' => 'App\Entity\General\GenImpuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where("i.codigoImpuestoTipoFk = 'R'")
                        ->orderBy('i.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Retencion:'
                , 'required' => true])
            ->add('impuestoIvaVentaRel', EntityType::class, [
                'class' => 'App\Entity\General\GenImpuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where("i.codigoImpuestoTipoFk = 'I'")
                        ->orderBy('i.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Iva:'
                , 'required' => true])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurItem::class,
        ]);
    }
    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoItemPk",      "tipo":"pk"      ,"ayuda":"Codigo del registro"                      ,"titulo":"ID"},
            {"campo":"nombre",                "tipo":"texto"  ,"ayuda":"Nombre del aspirante",           "titulo":"NOMBRE CORTO"},          
            {"campo":"impuestoRetencionRel.codigoImpuestoPk",                     "tipo":"texto"   ,"ayuda":""                     ,              "titulo":"CARGO", "relacion":""},
            {"campo":"impuestoIvaVentaRel.codigoImpuestoPk",                     "tipo":"texto"   ,"ayuda":""                     ,              "titulo":"CARGO", "relacion":""}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoItemPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"nombre",                              "tipo":"TextType",      "propiedades":{"label":"Nombre"},     "operador":"like"}
        ]';
        return $campos;
    }
}