<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCategoryController extends AbstractController
{
    /**
     * Api manuelle 
     * Méthode pour faire apparaître toutes les catégories
     *
     * @param CategoryRepository $repo
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/api/categories', name: 'api_categories',methods:["GET"])]
    public function list(CategoryRepository $repo, SerializerInterface $serializer): Response
    {
        $categories = $repo->findAll();
        $resultat = $serializer->serialize(
            $categories,
            'json',
            ['groups'=>'listCategoryFull']
        );
        
        return new JsonResponse($resultat, 200,[],true);
    }

    /**
     * Api manuelle 
     * Méthode pour faire apparaître une seule catégorie, selon Id.
     *
     * @param Category $category
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/api/category/{id}', name: 'api_category_show',methods:["GET"])]
    public function show(Category $category, SerializerInterface $serializer): Response
    {
        $resultat = $serializer->serialize(
            $category,
            'json',
            ['groups'=>'listCategorySimple']
        );
        
        return new JsonResponse($resultat,Response :: HTTP_OK,[],true);
    }

    /**
     * Api manuelle 
     * Méthode pour creer une nouvelle catégorie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/api/category', name: 'api_category_create',methods:["POST"])]
    public function create(Request $request,EntityManagerInterface $manager, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $category=new Category();
        //$serializer->deserialize($data, Category::class,'json',['object_to_populate'=>$category]);
        $category=$serializer->deserialize($data, Category::class,'json');
        // Gestion des erreurs de validation
        $errors=$validator->validate($category);
        if(count($errors))
        {
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($category);
        $manager->flush();

        return new JsonResponse(
            "Le catégorie a bien été créé",
            Response :: HTTP_CREATED,
            // Après la création, redirection sur la vue de la catégorie créée.
            //["location"=>"api/category/".$category->getId()],
            ["location"=>$this->generateUrl(
                'api_category_show',
                ["id"=>$category->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            true
        );
       
    }

    /**
     * Api manuelle 
     * Méthode pour modifier une catégorie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager@param 
     * @param Category $category
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/api/category/{id}', name: 'api_category_update',methods:["PUT"])]
    public function update(Request $request,EntityManagerInterface $manager, Category $category, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $serializer->deserialize($data, Category::class,'json',['object_to_populate'=>$category]); 
         
        // Gestion des erreurs de validation
         $errors=$validator->validate($category);
         if(count($errors))
         {
             $errorsJson=$serializer->serialize($errors,'json');
             return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
         }
        $manager->persist($category);
        $manager->flush();
        
        return new JsonResponse('La catégorie a bien été modifié',Response :: HTTP_OK,[],true);
    }

    
    /**
     * Api manuelle 
     * Méthode pour supprimer une catégorie
     *
     * @param EntityManagerInterface $manager@param 
     * @param Category $category
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/api/category/{id}', name: 'api_category_delete',methods:["DELETE"])]
    public function delete(EntityManagerInterface $manager, Category $category, SerializerInterface $serializer): Response
    {
        $manager->remove($category);
        $manager->flush();
        
        return new JsonResponse('La catégorie a bien été supprimé',Response :: HTTP_OK,[]);
    }
}
