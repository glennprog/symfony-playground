<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class Paginator
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack){
        $this->setRequestStack($requestStack);
    }

    public function getPaginatorAttributes(){ // Put this in a service
        $request = $this->getRequestStack()->getCurrentRequest();
        if($request->isXMLHttpRequest()){
            $page = $request->request->get('page');
            $count = $request->request->get('count');
            $orderBy = $request->request->get('orderBy');
            if($orderBy =="" || $orderBy == null){
                $orderBy = null;
            }
            return array('page' => $page, 'count' => $count, 'orderBy' => $orderBy);
        }
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

    protected function getRequestStack(){
		return $this->requestStack;
	}

	protected function setRequestStack($requestStack){
		$this->requestStack = $requestStack;
	}
}
