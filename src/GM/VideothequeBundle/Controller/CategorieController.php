<?php

namespace GM\VideothequeBundle\Controller;

use GM\VideothequeBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function indexAction(Request $request)
    {
        $this->securityGuardianAccess();
        $paginatorAttributes = $this->get('paginator')->getPaginatorAttributes($request); 
        $page = $paginatorAttributes['page'];
        $count = $paginatorAttributes['count'];
        $orderBy = $paginatorAttributes['orderBy'];
        $categorie_handler = $this->get('categorie_handler');
        if($this->get('security.authorization_checker')->isGranted('ROLE_AMDIN')){
            $criteria = array(); // It means all data
            $categories = $categorie_handler->onReadBy($criteria,'GMVideothequeBundle:Categorie', $page, $count, $orderBy);
        }
        else{
            $criteria = array('owner'=>$this->getUser()->getId());
            $maxCategoriesEntities = $categorie_handler->maxEntities($criteria, 'GMVideothequeBundle:Categorie');
            $categories = $categorie_handler->onReadBy($criteria,'GMVideothequeBundle:Categorie', $page, $count, $orderBy);
            $paginator_categories = $this->get('paginator')->paginator($page, $count, $maxCategoriesEntities, count($categories), $criteria, $this->getRoute('index'), "categories");
            $paginator = array(
                "categories" => $paginator_categories
            );
        }
        if($request->isXMLHttpRequest()){
            return new JsonResponse(array(
                'categories' => $categories,
                'paginator' => $paginator,
            ));
        }
        return $this->render($this->getTwig('index'), array('categories' => $categories, 'paginator' => $paginator));
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
            $criteria = array('id' => $id);
            $categories = $categorie_handler->onReadBy($criteria,'GMVideothequeBundle:Categorie');

            /*
            $criteria = array();
            $films = $film_handler->onReadBy($criteria,'GMVideothequeBundle:Film', $page, $count, $orderBy);
            */
        }
        else if($categorie->isOwner($this->getUser()->getId() )){
            $criteria = array('owner'=>$this->getUser()->getId(), 'id' => $id);
            $categories = $categorie_handler->onReadBy($criteria,'GMVideothequeBundle:Categorie');


            // Get film

            $paginatorAttributes = $this->get('paginator')->getPaginatorAttributes($request); 
            $page = $paginatorAttributes['page'];
            $count = $paginatorAttributes['count'];
            $orderBy = $paginatorAttributes['orderBy'];

            $film_handler = $this->get('film_handler');
            $criteria = array('owner'=>$this->getUser()->getId(), 'categorie'=> $id);
            $maxfilmsEntities = $film_handler->maxEntities($criteria, 'GMVideothequeBundle:Film');
            $films = $film_handler->onReadBy($criteria,'GMVideothequeBundle:Film', $page, $count, $orderBy);

            $route = array(
                'route_name' => 'categorie_show',
                'params' => array('id' => $id)
            );

            $paginator_films = $this->get('paginator')->paginator($page, $count, $maxfilmsEntities, count($films), $criteria, $route, "films");
            $paginator = array(
                "films" => $paginator_films
            );

            if($request->isXMLHttpRequest()){
                return new JsonResponse(array(
                    'films' => $films,
                    'paginator' => $paginator,
                    'categories' => $categories,
                ));
            }

            //$film_handler = $this->get('film_handler');
            //$films = $film_handler->getFilmsOfCategorieForUser($this->getUser()->getId(), $id);
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
            return $this->redirectToRoute('categorie_index');
        }
        return $this->render($this->getTwig('show'), array('films'=>$films, 'paginator' => $paginator, 'categorie' => $categorie, 'delete_form' => $this->getDeleteFormById($id)->createView()));
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
    public function deleteAction(Request $request, $id, Categorie $categorie){
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

    public function getRoute($template = 'index'){
        $listRoutes = array(
            'new' => 'categorie_new',
            'index' => 'categorie_index',
            'show' => 'categorie_show',
            'edit' => 'categorie_edit',
        );
        return $listRoutes[$template];
    }

    public function securityGuardianAccess($role = 'ROLE_USER'){
        $this->denyAccessUnlessGranted($role, null, 'Unable to access this page!');
    }


    public function delete_allAction(){
        $em = $this->getDoctrine()->getManager();
        $this->get('categorie_handler')->onDeleteAll($this->getUser()->getId(), $batch_size = 20);
        return $this->redirectToRoute('categorie_index');
    }
}
