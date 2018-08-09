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
    public function indexAction(Request $request)
    {
        $this->securityGuardianAccess(); // Calling of security Guardian
        $criterias = $this->get('categorie_handler')->getCriterias();
        $criterias['criteria-where'] = $this->getBaseCriterias_categorie();
        
        $criterias['pagination']['route']['route_name'] = ( $this->get('categorie_handler')->getRoute('rest_index') ); // Using API to call json data
        $categories = $this->get('query_manager')->findByCriterias( $criterias ); // Get query's result

        /*
        // Use this control if you don't use REST API to all json data.
        if ($request->isXmlHttpRequest() ) { //|| $request->query->get('showJson') == 1
            $delete_all_categories_url = $this->generateUrl('rest_categorie_delete_all');
            return new JsonResponse(array('data' => array('test' => true, 'categories' => $categories, 'delete_all_categories_url' => $delete_all_categories_url), 'msg' => 'OK', 'status' => 200));
        }
        */

        return $this->render(
            $this->get('categorie_handler')->getTwig('index'), 
            array(
                'categories' => $categories
            ));
    }

    public function showAction(Request $request, Categorie $categorie,  $id)
    {
        $this->securityGuardianAccess(); // Calling of security Guardian
        if($categorie->isOwner($this->getUser()->getId() )){ // Verify if request is allowed for the current user
            $FilmsParCategorie = $this->FilmsParCategorie($id);
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
            return $this->redirectToRoute('categorie_index');
        }
        return $this->render(
            $this->get('categorie_handler')->getTwig('show'), 
            array(
                'categorie' => $categorie, 
                'delete_form' => $this->getDeleteFormById($id)->createView(),
                'films' => $FilmsParCategorie
            ));
    }

    public function newAction(Request $request)
    {
        $this->securityGuardianAccess();
        $categorie = new Categorie($this->getUser());
        $form = $this->get('form_manager')->createForm(CategorieType::class, $categorie, array(), 'create');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $resultInsertData = $em->flush();
            $msgGen = $this->get('message_generator')->Msg_InsertionDB_OK();
            $request->getSession()->getFlashBag()->add("success", $msgGen);
            return $this->redirectToRoute('categorie_show', array('id' => $categorie->getId()));
        }
        return $this->render(
            $this->get('categorie_handler')->getTwig('new'), 
            array(
                'form' => $form->createView()
            ));
    }

    public function editAction(Request $request, Categorie $categorie, $id)
    {
        $this->securityGuardianAccess();
        if($categorie->isOwner($this->getUser()->getId()) ){

            $editForm = $this->get('form_manager')->createForm(CategorieType::class, $categorie, array(), 'update');
            $editForm->handleRequest($request);
            if ($editForm->isSubmitted() && $editForm->isValid()) 
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($editForm->getData());
                $resultUpdateData = $em->flush();
                $msgGen = $this->get('message_generator')->Msg_UpdateDB_OK();
                $request->getSession()->getFlashBag()->add("success", $msgGen);
                return $this->redirectToRoute('categorie_edit', array('id' => $categorie->getId()));
            }
            return $this->render(
                $this->get('categorie_handler')->getTwig('edit'), 
                array(
                    'categorie' => $categorie, 
                    'edit_form' => $editForm->createView(), 
                    'delete_form' => $this->getDeleteFormById($id)->createView()
                ));
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_Action_FAIL();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
            return $this->redirectToRoute('categorie_index');
        }
    }

    public function deleteAction(Request $request, $id, Categorie $categorie)
    {
        $criterias = $this->get('categorie_handler')->getCriterias();
        $criterias['pagination']['enabled'] = false;
        $criterias['criteria-where'] = $this->getBaseCriterias_categorie();
        $criterias['criteria-where'][] = 
                array(
                    'criterias' => array(
                        array(
                                'column' => array(
                                'name' => 'id',
                                'value' => $id
                            ),
                            'operator' => array(
                                'affectation' => '=',
                                'condition' => null
                            ),
                        ),
                    ),
                    'criterias-condition' => 'and'
                );
        $delation_ok = $this->get('query_manager')->onDeleteByCriterias($criterias, $batch_size = 5);
        if($delation_ok){
            $msgGen = $this->get('message_generator')->Msg_DeleteDB_OK();
            $request->getSession()->getFlashBag()->add("success", $msgGen);
            
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_DeleteDB_NONE();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
        }
        return $this->redirectToRoute('categorie_index');
    }

    public function delete_allAction(Request $request)
    {
        $criterias = $this->get('categorie_handler')->getCriterias();
        $criteria['pagination']['enabled'] = false;
        $criterias['criteria-where'] = $this->getBaseCriterias_categorie();
        $delation_ok = $this->get('query_manager')->onDeleteByCriterias($criterias, $batch_size = 20);
        if($delation_ok){
            $msgGen = $this->get('message_generator')->Msg_DeleteDB_OK();
            $request->getSession()->getFlashBag()->add("success", $msgGen);
        }
        else{
            $msgGen = $this->get('message_generator')->Msg_DeleteDB_NONE();
            $request->getSession()->getFlashBag()->add("warning", $msgGen);
        }
        return $this->redirectToRoute('categorie_index');
    }

    public function getDeleteFormById($id)
    {
        $option_delete_form = array('action' => $this->generateUrl('categorie_delete', array('id' => $id)), 'method' => 'DELETE');
        $delete_form = $this->get('form_manager')->createForm(FormType::class, new categorie($this->getUser()), $option_delete_form, 'delete');
        return $delete_form;
    }

    public function securityGuardianAccess($role = 'ROLE_USER'){
        $this->denyAccessUnlessGranted($role, null, 'Unable to access this page!');
    }

    public function getBaseCriterias_categorie()
    {
        $criteria = array( 
            array(
                'criterias' => array(
                    array(
                            'column' => array(
                            'name' => 'owner',
                            'value' => $this->getUser()->getId()
                        ),
                        'operator' => array(
                            'affectation' => '=',
                            'condition' => null
                        ),
                    ),
                ),
                'criterias-condition' => null
            )
        );
        return $criteria;
    }

    public function getBaseCriterias_film()
    {
        $criteria = array( 
            array(
                'criterias' => array(
                    array(
                            'column' => array(
                            'name' => 'owner',
                            'value' => $this->getUser()->getId()
                        ),
                        'operator' => array(
                            'affectation' => '=',
                            'condition' => null
                        ),
                    ),
                ),
                'criterias-condition' => null
            )
        );
        return $criteria;
    }
    
    public function FilmsParCategorie($categorieID)
    {
        $criterias = $this->get('film_handler')->getCriterias();
        $criterias['pagination']['route'] = array(
                'route_name' => $this->get('categorie_handler')->getRoute('rest_show_film_par_categorie'), //rest_show_film_par_categorie
                'params' => array('id' => $categorieID)
            );
        $criterias['criteria-where'] = $this->getBaseCriterias_film();
        $criterias['criteria-where'][] = 
                array(
                    'criterias' => array(
                        array(
                                'column' => array(
                                'name' => 'categorie',
                                'value' => $categorieID
                            ),
                            'operator' => array(
                                'affectation' => '=',
                                'condition' => null
                            ),
                        ),
                    ),
                    'criterias-condition' => 'and'
                );    
        $films = $this->get('query_manager')->findByCriterias( $criterias ); // Get query's result
        return $films;
    }
}
