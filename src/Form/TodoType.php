<?php

namespace App\Form;

use App\Entity\Todo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer un titre pour la tâche'
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Le titre de la tâche doit contenir au moins {{ limit }} caractères.',
                        'max' => 100,
                        'maxMessage' => 'Le titre de la tâche doit contenir au maximum {{ limit }} caractères.',
                    ]),
                ]
                
            ])
            
            ->add('content',TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer un contenu cette tâche'
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => 'la tâche doit contenir au moins {{ limit }} caractères.',
                        
                    ]),
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
        ]);
    }
}
