<?php

namespace App\Form;

use App\Entity\Pin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class PinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class)
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Debate' => 'Debate',
                    'Question' => 'Question',
                    'Response' => 'Response',
                    'Information'=>'Information',
                    'Notification'=>'Notification',
                    'Final result'=>'Final result',
                ],])
            ->add('description',TextareaType::class)
            ->add('imageFile', VichImageType::class, [
                'label'=>'Image (JPG or PNG file)',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Delete',
                'download_uri' => false,
                'imagine_pattern'=>'thumbnail_small'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pin::class,
        ]);
    }
}
