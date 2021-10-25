<?php 
    $filter_names = array('type','topic');

    if(isset($_GET[$filter_names[0]]) || isset($_GET[$filter_names[1]])){
        $filters = array();
        foreach($filter_names as $filter_name){
            if($_GET[$filter_name]){
                $filters[$filter_name] = $_GET[$filter_name];
            }
        }    
    }else{
        if(!is_page(10) && isset($_COOKIE['filters-knowledge-sharing'])) {
            $url = parse_url($_COOKIE['filters-knowledge-sharing']);
            if($url['query']){
                parse_str($url['query'], $filters);	
            }
        }    
    } 
?> 