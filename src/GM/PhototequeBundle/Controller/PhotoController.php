<?php

namespace GM\PhototequeBundle\Controller;

use GM\PhototequeBundle\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use GM\PhototequeBundle\Form\PhotoType;

/**
 * Photo controller.
 * Author : Glenn Milingui : Architect, Developer
 */
class PhotoController extends Controller
{
    /**
     * Lists all photo entities.
     */
    public function indexAction(){
        $photo_handler = $this->get('photo_handler');
        $photos = $photo_handler->onReadBy('all',null,'GMPhototequeBundle:Photo');
        return $this->render($this->getTwig('index'), array('photos' => $photos));
    }

    /**
     * Creates a new Photo entity.
     */
    public function newAction(Request $request){
        $photo_handler = $this->get('photo_handler');
        if ($photo_handler->onCreate(new Photo($this->getUser()), PhotoType::class)) {
            return $this->redirectToRoute('photo_show', array('id' => $photo_handler->getEntityObj()->getId()));
        }
        return $this->render($this->getTwig('new'), array('form' => $photo_handler->getForm()->createView()));
    }

    /**
     * Finds and displays only one Photo entity. By Id.
     */
    public function showAction($id, Photo $photo){ // Benefits of Controller mechanic for retrieving photo entity directly in parameters.
        return $this->render($this->getTwig('show'), array('photo' => $photo, 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Displays a form to edit only one existing photo entity. By Id.
     */
    public function editAction($id, Photo $photo){
        $photo_handler = $this->get('photo_handler');
        if ($photo_handler->onUpdate($photo, PhotoType::class)) {
            return $this->redirectToRoute('photo_edit', array('id' => $photo->getId()));
        }
        return $this->render($this->getTwig('edit'), array('photo' => $photo, 'edit_form' => $photo_handler->getForm()->createView(), 'delete_form' => $this->getDeleteFormById($id)->createView()));
    }

    /**
     * Deletes only one photo entity. By Id.
     */
    public function deleteAction($id, Photo $photo){
        $this->get('photo_handler')->OnDelete($photo, "Deleting a photo entity with id = ".$id);
        return $this->redirectToRoute('photo_index');
    }

    public function getDeleteFormById($id){
        $option_delete_form = array('action' => $this->generateUrl('photo_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, new Photo($this->getUser()), $option_delete_form, 'delete');
        return $delete_form;
    }

    public function getTwig($template = 'index'){
        $listTemplates = array(
            'new' => 'GMPhototequeBundle:photo:new.html.twig',
            'index' => 'GMPhototequeBundle:photo:index.html.twig',
            'show' => 'GMPhototequeBundle:photo:show.html.twig',
            'edit' => 'GMPhototequeBundle:photo:edit.html.twig',
        );
        return $listTemplates[$template];
    }
}