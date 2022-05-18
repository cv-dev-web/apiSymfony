<?php

namespace App\Controller;

use App\Entity\Content;
use App\Repository\ContentRepository;
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

class ApiContentController extends AbstractController
{
    /**
     *  Api manuelle 
     *  Méthode pour faire apparaître tout les contenus
     *
     * @param ContentRepository $repo
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/api/contents', name: 'api_contents',methods:["GET"])]
    public function list(ContentRepository $repo, SerializerInterface $serializer): Response
    {
        $contents = $repo->findAll();
        $resultat = $serializer->serialize(
            $contents,
            'json',
            ['groups'=>'listContentFull']
        );
        
        return new JsonResponse($resultat, 200,[],true);
    }

    /**
     * Api manuelle 
     * Méthode pour faire apparaître un seul contenu, selon Id.
     *
     * @param Content $content
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/api/content/{id}', name: 'api_content_show',methods:["GET"])]
    public function show(Content $content, SerializerInterface $serializer): Response
    {
        $resultat = $serializer->serialize(
            $content,
            'json',
            ['groups'=>'listContentSimple']
        );
        
        return new JsonResponse($resultat,Response :: HTTP_OK,[],true);
    }
    /**
     * Api manuelle 
     * Méthode pour creer un nouveau contenu
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/api/content', name: 'api_content_create',methods:["POST"])]
    public function create(Request $request,EntityManagerInterface $manager, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $content=new Content();
        //$serializer->deserialize($data, Content::class,'json',['object_to_populate'=>$content]);
        $content=$serializer->deserialize($data, Content::class,'json');
        // Gestion des erreurs de validation
        $errors=$validator->validate($content);
        if(count($errors))
        {
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($content);
        $manager->flush();

        return new JsonResponse(
            "Le catégorie a bien été créé",
            Response :: HTTP_CREATED,
            //["location"=>"api/content/".$content->getId()],
            ["location"=>$this->generateUrl(
                'api_content_show',
                ["id"=>$content->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            true
        );
       
    }
    /**
     *  Api manuelle 
     *  Méthode pour modifier un contenu
     *
     * @param Request $request
     * @param EntityManagerInterface $manager@param 
     * @param Content $content
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return Response
     */
    #[Route('/api/content/{id}', name: 'api_content_update',methods:["PUT"])]
    public function update(Request $request,EntityManagerInterface $manager, Content $content, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $serializer->deserialize($data, Content::class,'json',['object_to_populate'=>$content]); 
         
        // Gestion des erreurs de validation
         $errors=$validator->validate($content);
         if(count($errors))
         {
             $errorsJson=$serializer->serialize($errors,'json');
             return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
         }
        $manager->persist($content);
        $manager->flush();
        
        return new JsonResponse('La catégorie a bien été modifié',Response :: HTTP_OK,[],true);
    }
    /**
     * Api manuelle 
     * Méthode pour supprimer une catégorie
     *
     * @param EntityManagerInterface $manager@param 
     * @param Content $content
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route('/api/content/{id}', name: 'api_content_delete',methods:["DELETE"])]
    public function delete(EntityManagerInterface $manager, Content $content, SerializerInterface $serializer): Response
    {
        $manager->remove($content);
        $manager->flush();
        
        return new JsonResponse('La catégorie a bien été supprimé',Response :: HTTP_OK,[]);
    }
}
