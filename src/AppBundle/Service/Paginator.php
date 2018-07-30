<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Paginator
{
    protected $requestStack;
    protected $router;

    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $router){
        $this->setRequestStack($requestStack);
        $this->router = $router;
    }

    public function getPaginatorAttributes(){
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
            $count = 5;
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

    public function paginator($page, $count, $total = null, $currentTotalReading, array $criteria = null, $route, $entityNameHandled){
        $init_read = false;
        if($page < 1 || $count < 1){
            $init_read = true;
        }
        $count = ($init_read) ? 1 : $count;
        $page = ($init_read) ? 1 : $page; // $count($page - 1)
        $paginator['count'] = $count;
        $paginator['total_page'] = intval(ceil($total / $count));
        $paginator['current_page'] = ($init_read) ? 1 : $page;
        $paginator['previous_page'] = ($paginator['current_page'] > 1) && ($paginator['current_page'] <= $paginator['total_page']) ? ($paginator['current_page'] - 1) : null;
        $paginator['next_page'] = ($total - ($page * $count) > 0) ? ($page + 1) : null;
        $paginator['next_record_to_read'] = ($paginator['next_page'] != null) ? ($total - ($count * $page)) : null;
        $init_read = false;
        $paginator['total_entities'] = $total;


        $paginator_prev = $this->router->generate(
            $route['route_name'], 
            array(
            'page' => $paginator['previous_page'],
            'count' => $paginator['count']));
        
        
        $paginator_prev_fast = $this->router->generate(
            $route['route_name'], 
            array(
            'page' => 1,
            'count' => $paginator['count']));
        
        
        $paginator_next = $this->router->generate(
            $route['route_name'],
            array(
            'page' => $paginator['next_page'], 
            'count' => $paginator['count']));
        
        $paginator_next_fast = $this->router->generate(
            $route['route_name'], 
            array(
            'page' => $paginator['total_page'], 
            'count' => $paginator['count']));

        $paginator["entity"] = $entityNameHandled;
        $paginator["paginator_prev"] = $paginator_prev;
        $paginator["paginator_prev_fast"] = $paginator_prev_fast;
        $paginator["paginator_next"] = $paginator_next;
        $paginator["paginator_next_fast"] = $paginator_next_fast;
        return $paginator;
    }

    protected function getRequestStack(){
		return $this->requestStack;
	}

	protected function setRequestStack($requestStack){
		$this->requestStack = $requestStack;
    }
    /*

    public function paginator($page, $count, $total = null, $currentTotalReading, array $criteria = null, $route, $entityNameHandled){
        $init_read = false;
        if($page < 1 || $count < 1){
            $init_read = true;
        }
        $count = ($init_read) ? 1 : $count;
        $page = ($init_read) ? 1 : $page; // $count($page - 1)
        $paginator['count'] = $count;
        $paginator['total_page'] = intval(ceil($total / $count));
        $paginator['current_page'] = ($init_read) ? 1 : $page;
        $paginator['previous_page'] = ($paginator['current_page'] > 1) && ($paginator['current_page'] <= $paginator['total_page']) ? ($paginator['current_page'] - 1) : null;
        $paginator['next_page'] = ($total - ($page * $count) > 0) ? ($page + 1) : null;
        $paginator['next_record_to_read'] = ($paginator['next_page'] != null) ? ($total - ($count * $page)) : null;
        $init_read = false;
        $paginator['total_entities'] = $total;


        dump(($route['params'] != null) ? $route['params']['id']:null);
        $paginator_prev = $this->router->generate(
            $route['route_name'], 
            array( 'id' => ($route['params'] != null) ? $route['params']['id']:null, 
            'page' => $paginator['previous_page'],
            'count' => $paginator['count']));
        
        
        $paginator_prev_fast = $this->router->generate(
            $route['route_name'], 
            array( 'id' => ($route['params']['id'] != null) ? $route['params']['id']:null, 
            'page' => 1,
            'count' => $paginator['count']));
        
        
        $paginator_next = $this->router->generate(
            $route['route_name'],
            array( 'id' => ($route['params']['id'] != null) ? $route['params']['id']:null, 
            'page' => $paginator['next_page'], 
            'count' => $paginator['count']));
        
        
        $paginator_next_fast = $this->router->generate(
            $route['route_name'], 
            array( 'id' => ($route['params']['id'] != null) ? $route['params']['id']:null, 
            'page' => $paginator['total_page'], 
            'count' => $paginator['count']));

        $paginator["entity"] = $entityNameHandled;
        $paginator["paginator_prev"] = $paginator_prev;
        $paginator["paginator_prev_fast"] = $paginator_prev_fast;
        $paginator["paginator_next"] = $paginator_next;
        $paginator["paginator_next_fast"] = $paginator_next_fast;
        return $paginator;
    }
    */
}
