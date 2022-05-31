<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Pin;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Form\PinType;
use App\Repository\PinRepository;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{

    #[Route('/', name: 'app_home',methods:'GET')]
    public function index(PinRepository $pinRepository,PaginatorInterface $paginator,Request $request): Response
    {

        $pins=$pinRepository->findBy([],['createdAt'=>'DESC']);
        $pagination = $paginator->paginate(
            $pins,
            $request->query->getInt('page',1),
            8
        );
        $pins=$pagination;
        return $this->render('pins/index.html.twig', compact('pins'));
    }


    #[Route('/pins/{id<[0-9]+>}', name: 'app_pins_show',methods:'GET|POST')]
    public function show(Pin $pin,Request $request,EntityManagerInterface $em): Response
    {    

        //Part of comment section

        //generate form
        $comment=new Comment();
        $commentForm=$this->createForm(CommentType::class,$comment);
        $commentForm->handleRequest($request);
        
        //submit and validation
        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $flash=null;
        
            //recuperate parentid GET to parent comment
            $parentId=$commentForm->get('parentid')->getData();

            //add parent to comment which has parentid injected by formbuilder
            if($parentId!=null) 
            {
                $parent=$em->getRepository(Comment::class)->find($parentId);
                $comment->setParent($parent);
                $flash="Reply";
                
            }
            
            
            $comment->setPinId($pin);
            $comment->setUserId($this->getUser());
            
            $em->persist($comment);
            $em->flush();


            $this->addFlash('success',($flash)?"$flash successfully added":"Comment".' successfully added');
            return $this->redirectToRoute('app_pins_show',['id'=>$pin->getId()]);

        }



       
        return $this->renderForm('pins/show.html.twig', compact('pin','commentForm'));
    }




    #[Route('/pins/create', name: 'app_pins_create',methods:'GET|POST')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request,EntityManagerInterface $em): Response
    {
        $pin=new Pin();

        $form=$this->createForm(PinType::class,$pin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success','Pin successfully created');
            return $this->redirectToRoute('app_home');
        }


        
        return $this->renderForm('pins/create.html.twig',compact('form'));
    }




    #[Route('/pins/{id<[0-9]+>}/edit', name: 'app_pins_edit',methods:'GET|POST')]
    #[IsGranted("PIN_MANAGE",subject:"pin",message:'No access! Get out!',statusCode:403)]
    public function edit(Pin $pin,Request $request,EntityManagerInterface $em): Response
    {
        $form=$this->createForm(PinType::class,$pin);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
    
            $em->flush();


            $this->addFlash('success','Pin successfully updated');
            return $this->redirectToRoute('app_home');
        }


        
        return $this->renderForm('pins/edit.html.twig', compact('form','pin'));
    }


    #[Route('/pins/{id<[0-9]+>}/delete', name: 'app_pins_delete',methods:'GET')]
    #[IsGranted("PIN_MANAGE",subject:"pin",message:'No access! Get out!',statusCode:403)]
    public function delete(Pin $pin,EntityManagerInterface $em): Response
    {
        $em->remove($pin);
        $em->flush();


        $this->addFlash('info','Pin successfully deleted');
        return $this->redirectToRoute('app_home');

    }

}
