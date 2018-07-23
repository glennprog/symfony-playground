<?php

namespace GM\VideothequeBundle\Controller;

use GM\VideothequeBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use GM\VideothequeBundle\Form\CategorieType;

/**
 * Categorie controller.
 *
 */
class CategorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     */
    public function indexAction()
    {
        $this->securityGuardianAccess();
        $categorie_handler = $this->get('categorie_handler');
        if($this->get('security.authorization_checker')->isGranted('ROLE_AMDIN')){
            $categories = $categorie_handler->onReadBy('all',null,'GMVideothequeBundle:Categorie');
        }
        else{
            $categories = $categorie_handler->onReadBy('owner',$this->getUser()->getId(),'GMVideothequeBundle:Categorie');
            //$categories = $categorie_handler->onReadBy('all',null,'GMVideothequeBundle:Categorie');
        }
        return $this->render($this->getTwig('index'), array('categories' => $categories));
    }

    /**
     * Creates a new categorie entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->securityGuardianAccess();
        $categorie_handler = $this->get('categorie_handler');
        if ($categorie_handler->onCreate(new Categorie($this->getUser()), CategorieType::class)) {
            return $this->redirectToRoute('categorie_show', array('id' => $categorie_handler->getEntityObj()->getId()));
        }
        return $this->render($this->getTwig('new'), array('form' => $categorie_handler->getForm()->createView()));
    }

    /**
     * Finds and displays a categorie entity.
     *
     */
    public function showAction(Request $request, Categorie $categorie,  $id)
    {
        $this->securityGuardianAccess();
        $categorie_handler = $this->get('categorie_handler');
        if($this->get('security.authorization_checker')->isGranted('ROLE_AMDIN')){
            $categories = $categorie_handler->onReadBy('id',$id,'GMVideothequeBundle:Categorie');
        }
        else if($categorie->isOwner($this->getUser()->getId() )){
            $categories = $categorie_handler->onReadBy('owner',$this->getUser()->getId(),'GMVideothequeBundle:Categorie');
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
            return $this->redirectToRoute('categorie_index');
        }
        return $this->render($this->getTwig('show'), array('categorie' => $categorie, 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     */
    public function editAction(Request $request, Categorie $categorie, $id)
    {
        $this->securityGuardianAccess();
        if($categorie->isOwner($this->getUser()->getId()) || $this->get('security.authorization_checker')->isGranted('ROLE_AMDIN') ){
            $categorie_handler = $this->get('categorie_handler');
            if ($categorie_handler->onUpdate($categorie, CategorieType::class)) {
                return $this->redirectToRoute('categorie_edit', array('id' => $categorie->getId()));
            }
            return $this->render($this->getTwig('edit'), array('categorie' => $categorie, 'edit_form' => $categorie_handler->getForm()->createView(), 'delete_form' => $this->getDeleteFormById($id)->createView()));
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
            return $this->redirectToRoute('categorie_index');
        }
    }

    /**
     * Deletes only one categorie entity. By Id.
     */
    public function deleteAction($id, Categorie $categorie){
        $this->securityGuardianAccess();
        if($categorie->isOwner($this->getUser()->getId()) || $this->get('security.authorization_checker')->isGranted('ROLE_AMDIN') ){
            $this->get('categorie_handler')->OnDelete($categorie, "Deleting a categorie entity with id = ".$id);
            return $this->redirectToRoute('categorie_index');
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
            return $this->redirectToRoute('categorie_index');
        }
    }

    public function getDeleteFormById($id){
        $option_delete_form = array('action' => $this->generateUrl('categorie_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, new categorie($this->getUser()), $option_delete_form, 'delete');
        return $delete_form;
    }

    public function getTwig($template = 'index'){
        $listTemplates = array(
            'new' => 'GMVideothequeBundle:categorie:new.html.twig',
            'index' => 'GMVideothequeBundle:categorie:index.html.twig',
            'show' => 'GMVideothequeBundle:categorie:show.html.twig',
            'edit' => 'GMVideothequeBundle:categorie:edit.html.twig',
        );
        return $listTemplates[$template];
    }

    public function securityGuardianAccess($role = 'ROLE_USER'){
        $this->denyAccessUnlessGranted($role, null, 'Unable to access this page!');
    }
}
