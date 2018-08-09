<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchEngineManager
{
    protected $requestStack;
    protected $router;

    public function __construct(RequestStack $requestStack){
        $this->setRequestStack($requestStack);
    }

    protected function getRequestStack(){
		return $this->requestStack;
	}

	protected function setRequestStack($requestStack){
		$this->requestStack = $requestStack;
    }

    public function getSearchByCriterias(){
        $request = $this->getRequestStack()->getCurrentRequest();

        if($request->query->get('searchBy') != null){
            $searchBy = $request->query->get('searchBy');
        }
        elseif($request->attributes->get('searchBy') != null){
            $searchBy = $request->attributes->get('searchBy');
        }
        elseif ( $searchBy = $request->request->get('searchBy')!= null ) {
            $searchBy = $request->request->get('searchBy');
        }
        else{
            $searchBy = null;
        }

        if($request->query->get('searchByMode') != null){
            $searchByMode = $request->query->get('searchByMode');
        }
        elseif($request->attributes->get('searchByMode') != null){
            $searchByMode = $request->attributes->get('searchByMode');
        }
        elseif ( $request->request->get('searchByMode')!= null ) {
            $searchByMode = $request->request->get('searchByMode');
        }
        else{
            $searchByMode = 'equal';
        }

        return array('searchBy' => $searchBy, 'searchByMode' => $searchByMode);
    }

    public function getCriterias($searchByCriterias = null){
        if($searchByCriterias == null){
            $searchByCriterias = $this->getSearchByCriterias();
        }
        if($searchByCriterias['searchBy'] == null){
            return null;
        }
        $criterias = null;
        switch ($searchByCriterias['searchByMode']) {
            case 'equal':
                $criterias =  $this->getSearchCriterias_advanced_equal($searchByCriterias);
                break;
            
            case 'data_percentage':
                $criterias =  $this->getSearchCriterias_advanced_like_data_percentage($searchByCriterias);
                break;
            /*
            case 'percentage_data': // To test and confirm the functionnality of ...
                $criterias =  $this->getSearchCriterias_advanced_like_percentage_data($searchByCriterias);
                break;
            */
            case 'percentage_data_percentage':
                $criterias =  $this->getSearchCriterias_advanced_like_percentage_data_percentage($searchByCriterias);
                break;
                
            default:
                $criterias =  $this->getSearchCriterias_advanced_equal($searchByCriterias);
                break;
        }
        return $criterias;
    }

    public function getSearchCriterias_advanced_equal($searchByCriterias = null){
        if($searchByCriterias == null){
            $searchByCriterias = $this->getSearchByCriterias();
        }
        if($searchByCriterias['searchBy'] == null){
            return null;
        }
        $client_data_searchBy = json_decode($searchByCriterias['searchBy']);
        
        $criterias = array();
        $paramSearchBy = array();
        $begin = true;
        foreach ($client_data_searchBy as $name => $value) {
            $paramSearchBy['column'] = array(
                'name' => $name,
                'value' => $value,
            );
            if($begin){
                $paramSearchBy['operator'] = array(
                    'affectation' => '=',
                    'condition' => null,
                );
                $begin = false;
            }
            else{
                $paramSearchBy['operator'] = array(
                    'affectation' => '=',
                    'condition' => 'or',
                );
            }
            $criterias['criterias'][] = $paramSearchBy;   
        }
        $criterias['criterias-condition'] = 'and';
        return $criterias;
    }

    public function getSearchCriterias_advanced_like_data_percentage($searchByCriterias = null){
        if($searchByCriterias == null){
            $searchByCriterias = $this->getSearchByCriterias();
        }
        if($searchByCriterias['searchBy'] == null){
            return null;
        }
        $client_data_searchBy = json_decode($searchByCriterias['searchBy']);
        $criterias = array();
        $paramSearchBy = array();
        $begin = true;
        foreach ($client_data_searchBy as $name => $value) {
            $paramSearchBy['column'] = array(
                'name' => $name,
                'value' => $value . "%",
            );
            if($begin){
                $paramSearchBy['operator'] = array(
                    'affectation' => 'LIKE',
                    'condition' => null,
                );
                $begin = false;
            }
            else{
                $paramSearchBy['operator'] = array(
                    'affectation' => 'LIKE',
                    'condition' => 'or',
                );
            }
            $criterias['criterias'][] = $paramSearchBy;   
        }
        $criterias['criterias-condition'] = 'and';
        return $criterias;
    }

    // To test and confirm the functionnality of ...
    public function getSearchCriterias_advanced_like_percentage_data($searchByCriterias = null){
        if($searchByCriterias == null){
            $searchByCriterias = $this->getSearchByCriterias();
        }
        if($searchByCriterias['searchBy'] == null){
            return null;
        }
        $client_data_searchBy = json_decode($searchByCriterias['searchBy']);
        $criterias = array();
        $paramSearchBy = array();
        $begin = true;
        foreach ($client_data_searchBy as $name => $value) {
            $paramSearchBy['column'] = array(
                'name' => $name,
                'value' => "%" . $value,
            );
            if($begin){
                $paramSearchBy['operator'] = array(
                    'affectation' => 'LIKE',
                    'condition' => null,
                );
                $begin = false;
            }
            else{
                $paramSearchBy['operator'] = array(
                    'affectation' => 'LIKE',
                    'condition' => 'or',
                );
            }
            $criterias['criterias'][] = $paramSearchBy;   
        }
        $criterias['criterias-condition'] = 'and';
        return $criterias;
    }

    public function getSearchCriterias_advanced_like_percentage_data_percentage($searchByCriterias = null){
        if($searchByCriterias == null){
            $searchByCriterias = $this->getSearchByCriterias();
        }
        if($searchByCriterias['searchBy'] == null){
            return null;
        }
        $client_data_searchBy = json_decode($searchByCriterias['searchBy']);
        
        $criterias = array();
        $paramSearchBy = array();
        $begin = true;
        foreach ($client_data_searchBy as $name => $value) {
            $paramSearchBy['column'] = array(
                'name' => $name,
                'value' => "%" . $value . "%",
            );
            if($begin){
                $paramSearchBy['operator'] = array(
                    'affectation' => 'LIKE',
                    'condition' => null,
                );
                $begin = false;
            }
            else{
                $paramSearchBy['operator'] = array(
                    'affectation' => 'LIKE',
                    'condition' => 'or',
                );
            }
            $criterias['criterias'][] = $paramSearchBy;   
        }
        $criterias['criterias-condition'] = 'and';
        return $criterias;
    }
}