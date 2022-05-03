<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
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

class ApiResourceController extends AbstractController
{
    #[Route('/api/resources', name: 'api_resources',methods:["GET"])]
    public function list(ResourceRepository $repo, SerializerInterface $serializer): Response
    {
        $resources = $repo->findAll();
        $resultat = $serializer->serialize(
            $resources,
            'json',
            ['groups'=>'listResourceFull']
        );
        
        return new JsonResponse($resultat, 200,[],true);
    }

    #[Route('/api/resource/{id}', name: 'api_resource_show',methods:["GET"])]
    public function show(Resource $resource, SerializerInterface $serializer): Response
    {
        $resultat = $serializer->serialize(
            $resource,
            'json',
            ['groups'=>'listResourceSimple']
        );
        
        return new JsonResponse($resultat,Response :: HTTP_OK,[],true);
    }

    #[Route('/api/resource', name: 'api_resource_create',methods:["POST"])]
    public function create(Request $request,EntityManagerInterface $manager, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $resource=new Resource();
        //$serializer->deserialize($data, Resource::class,'json',['object_to_populate'=>$resource]);
        $resource=$serializer->deserialize($data, Resource::class,'json');
        // Gestion des erreurs de validation
        $errors=$validator->validate($resource);
        if(count($errors))
        {
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($resource);
        $manager->flush();

        return new JsonResponse(
            "Le catégorie a bien été créé",
            Response :: HTTP_CREATED,
            //["location"=>"api/resource/".$resource->getId()],
            ["location"=>$this->generateUrl(
                'api_resource_show',
                ["id"=>$resource->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            true
        );
       
    }

    #[Route('/api/resource/{id}', name: 'api_resource_update',methods:["PUT"])]
    public function update(Request $request,EntityManagerInterface $manager, Resource $resource, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $serializer->deserialize($data, Resource::class,'json',['object_to_populate'=>$resource]); 
         
        // Gestion des erreurs de validation
         $errors=$validator->validate($resource);
         if(count($errors))
         {
             $errorsJson=$serializer->serialize($errors,'json');
             return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
         }
        $manager->persist($resource);
        $manager->flush();
        
        return new JsonResponse('La catégorie a bien été modifié',Response :: HTTP_OK,[],true);
    }

    #[Route('/api/resource/{id}', name: 'api_resource_delete',methods:["DELETE"])]
    public function delete(EntityManagerInterface $manager, Resource $resource, SerializerInterface $serializer): Response
    {
        $manager->remove($resource);
        $manager->flush();
        
        return new JsonResponse('La catégorie a bien été supprimé',Response :: HTTP_OK,[]);
    }
}
