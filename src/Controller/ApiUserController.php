<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\GradeRepository;
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

class ApiUserController extends AbstractController
{
    #[Route('/api/users', name: 'api_users',methods:["GET"])]
    public function list(UserRepository $repo, SerializerInterface $serializer): Response
    {
        $users = $repo->findAll();
        $resultat = $serializer->serialize(
            $users,
            'json',
            ['groups'=>'listUserFull']
        );
        
        return new JsonResponse($resultat, 200,[],true);
    }

    #[Route('/api/user/{id}', name: 'api_user_show',methods:["GET"])]
    public function show(User $user, SerializerInterface $serializer): Response
    {
        $resultat = $serializer->serialize(
            $user,
            'json',
            ['groups'=>'listUserSimple']
        );
        
        return new JsonResponse($resultat,Response :: HTTP_OK,[],true);
    }

    #[Route('/api/user', name: 'api_user_create',methods:["POST"])]
    public function create(GradeRepository $repoGrade, Request $request,EntityManagerInterface $manager, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $dataTab=$serializer->decode($data,'json');
        $grade= $repoGrade->find($dataTab['grade']['id']);
        $user=new User();
        //Solution 1
        $serializer->deserialize($data, User::class,'json',['object_to_populate'=>$user]);
        $user->setGrade($grade);
        //Solution 2
        //$user=$serializer->deserialize($data, User::class,'json');
        // Gestion des erreurs de validation
        $errors=$validator->validate($user);
        if(count($errors))
        {
            $errorsJson=$serializer->serialize($errors,'json');
            return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
        }
        $manager->persist($user);
        $manager->flush();

        return new JsonResponse(
            "L'utilisateur a bien été créé",
            Response :: HTTP_CREATED,
            //["location"=>"api/user/".$user->getId()],
            ["location"=>$this->generateUrl(
                'api_user_show',
                ["id"=>$user->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
                )
            ],
            true
        );
       
    }

    #[Route('/api/user/{id}', name: 'api_user_update',methods:["PUT"])]
    public function update(GradeRepository $repoGrade,Request $request,EntityManagerInterface $manager, User $user, SerializerInterface $serializer,ValidatorInterface $validator): Response
    {
        $data=$request->getContent();
        $dataTab = $serializer->decode($data,'json');
        $grade= $repoGrade->find($dataTab['grade']['id']);
        // Solution 1( un objet dans un objet)
        $serializer->deserialize($data, User::class,'json',['object_to_populate'=>$user]);
        $user->setGrade($grade);

        // Solution 2 (objet séparé "a des erreurs")
        //$serializer->denormalize($dataTab['user'],User::class,null,['object_to_populate'=>$user]);
        
        // Gestion des erreurs de validation
         $errors=$validator->validate($user);
         if(count($errors))
         {
             $errorsJson=$serializer->serialize($errors,'json');
             return new JsonResponse($errorsJson,Response::HTTP_BAD_REQUEST,[],true);
         }
        $manager->persist($user);
        $manager->flush();
        
        return new JsonResponse(
            "L'utilisateur a bien été modifié",
            Response :: HTTP_OK,[],
            true
        );
    }

    #[Route('/api/user/{id}', name: 'api_user_delete',methods:["DELETE"])]
    public function delete(EntityManagerInterface $manager, User $user, SerializerInterface $serializer): Response
    {
        $manager->remove($user);
        $manager->flush();
        
        return new JsonResponse(
            "L'utilisateur a bien été supprimé",
            Response :: HTTP_OK,[]
        );
    }
}
