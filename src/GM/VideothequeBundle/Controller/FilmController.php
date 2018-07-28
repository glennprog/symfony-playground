<?php

namespace GM\VideothequeBundle\Controller;

use GM\VideothequeBundle\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use GM\VideothequeBundle\Form\FilmType;

/**
 * Film controller.
 *
 */
class FilmController extends Controller
{    /**
    * Lists all film entities.
    *
    */
   public function indexAction(Request $request)
   {
       $this->securityGuardianAccess();
       $paginatorAttributes = $this->getPaginatorAttributes($request);
       $page = $paginatorAttributes['page'];
       $count = $paginatorAttributes['count'];
       $orderBy = $paginatorAttributes['orderBy'];
       $film_handler = $this->get('film_handler');
       if($this->get('security.authorization_checker')->isGranted('ROLE_AMDIN')){
        $criteria = array();
        $films = $film_handler->onReadBy($criteria,'GMVideothequeBundle:Film', $page, $count, $orderBy);
       }
       else{
            $criteria = array('owner'=>$this->getUser()->getId());
            $films = $film_handler->onReadBy($criteria,'GMVideothequeBundle:Film', $page, $count, $orderBy);
       }
       return $this->render($this->getTwig('index'), array('films' => $films));
   }

   /**
    * Creates a new film entity.
    *
    */
   public function newAction(Request $request)
   {
       $this->securityGuardianAccess();
       $film_handler = $this->get('film_handler');
       $optionForm = array('owner_user_id' => $this->getUser()->getId());
       if ($film_handler->onCreate(new Film($this->getUser()), FilmType::class, $optionForm)) {
           return $this->redirectToRoute('film_show', array('id' => $film_handler->getEntityObj()->getId()));
       }
       return $this->render($this->getTwig('new'), array('form' => $film_handler->getForm()->createView()));
   }

   /**
    * Finds and displays a film entity.
    *
    */
   public function showAction(Request $request, Film $film,  $id)
   {
       $this->securityGuardianAccess();
       $film_handler = $this->get('film_handler');
       if($this->get('security.authorization_checker')->isGranted('ROLE_AMDIN')){
        $criteria = array('id' => $id);
        $films = $film_handler->onReadBy($criteria,'GMVideothequeBundle:Film');
       }
       else if($film->isOwner($this->getUser()->getId() )){
        $criteria = array('owner'=>$this->getUser()->getId(), 'id' => $id);
        $films = $film_handler->onReadBy($criteria,'GMVideothequeBundle:Film');
       }
       else{
           $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
           $request->getSession()->getFlashBag()->add("warning", $msgGen);
           return $this->redirectToRoute('film_index');
       }
       return $this->render($this->getTwig('show'), array('film' => $film, 'delete_form' => $this->getDeleteFormById($id)->createView()));
   }

   /**
    * Displays a form to edit an existing film entity.
    *
    */
   public function editAction(Request $request, Film $film, $id)
   {
        $this->securityGuardianAccess();
        if($film->isOwner($this->getUser()->getId()) || $this->get('security.authorization_checker')->isGranted('ROLE_AMDIN') ){
            $optionForm = array('owner_user_id' => $this->getUser()->getId());
            $film_handler = $this->get('film_handler');
            if ($film_handler->onUpdate($film, FilmType::class, $optionForm)) {
               return $this->redirectToRoute('film_edit', array('id' => $film->getId()));
            }
            return $this->render($this->getTwig('edit'), array('film' => $film, 'edit_form' => $film_handler->getForm()->createView(), 'delete_form' => $this->getDeleteFormById($id)->createView()));
        }
       else{
           $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
           $request->getSession()->getFlashBag()->add("warning", $msgGen);
           return $this->redirectToRoute('film_index');
        }
   }

   /**
    * Deletes only one film entity. By Id.
    */
   public function deleteAction(Request $request, $id, Film $film){
       $this->securityGuardianAccess();
       if($film->isOwner($this->getUser()->getId()) || $this->get('security.authorization_checker')->isGranted('ROLE_AMDIN') ){
           $this->get('film_handler')->OnDelete($film, "Deleting a film entity with id = ".$id);
           return $this->redirectToRoute('film_index');
       }
       else{
           $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
           $request->getSession()->getFlashBag()->add("warning", $msgGen);
           return $this->redirectToRoute('film_index');
       }
   }

   public function getDeleteFormById($id){
       $option_delete_form = array('action' => $this->generateUrl('film_delete', array('id' => $id)), 'method' => 'DELETE');
       $delete_form = $this->get('form_manager')->createForm(FormType::class, new film($this->getUser()), $option_delete_form, 'delete');
       return $delete_form;
   }

   public function getTwig($template = 'index'){
       $listTemplates = array(
           'new' => 'GMVideothequeBundle:film:new.html.twig',
           'index' => 'GMVideothequeBundle:film:index.html.twig',
           'show' => 'GMVideothequeBundle:film:show.html.twig',
           'edit' => 'GMVideothequeBundle:film:edit.html.twig',
       );
       return $listTemplates[$template];
   }

   public function securityGuardianAccess($role = 'ROLE_USER'){
       $this->denyAccessUnlessGranted($role, null, 'Unable to access this page!');
   }

    public function delete_allAction(){
        $em = $this->getDoctrine()->getManager();
        $this->get('film_handler')->onDeleteAll($this->getUser()->getId(), $batch_size = 20);
        return $this->redirectToRoute('film_index');
    }

    public function getPaginatorAttributes(Request $request){
        if($request->query->get('page') != null){
            $page = $request->query->get('page');
        }
        elseif($request->attributes->get('page') != null){
            $page = $request->attributes->get('page');
        }
        else{
            $page = 1;
        }

        if($request->query->get('count') != null){
            $count = $request->query->get('count');
        }
        elseif($request->attributes->get('count') != null){
            $count = $request->attributes->get('count');
        }
        else{
            $count = 4;
        }

        if($request->query->get('orderBy') != null){
            $orderBy = $request->query->get('orderBy');
        }
        elseif($request->attributes->get('orderBy') != null){
            $orderBy = $request->attributes->get('orderBy');
        }
        else{
            $orderBy = null;
        }

        return array('page' => $page, 'count' => $count, 'orderBy' => $orderBy);
    }
}
