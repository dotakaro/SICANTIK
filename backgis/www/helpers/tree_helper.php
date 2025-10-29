<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
/**
 * Created by Indra.
 * User: Indra
 * Date: 3/6/2015
 * Time: 8:04 AM
 */
function build_tree(array $elements, $parentId = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = build_tree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

function olLiTree($tree) {
    $out = '<ul>';

    foreach($tree as $key => $value) {
        $out.= '<li>';

        if(isset($value['children']) && is_array($value['children']) && !empty($value['children'])){
            $out.= $value['data_key'] . olLiTree($value['children']);
        }else {
            $out.= $value['data_key'];
        }

        $out.= '</li>';
    }

    $out.= '</ul>';

    return $out;
}

function buildMenuTree(array $elements, $parentId = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element['parent'] == $parentId) {
            $children = buildMenuTree($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

function olLiMenuTree($tree, $isTop = false, $username = null) {
    $noAnchorLink = array('','#');
    $out = '<ul>';
    if($isTop){
        $out = '<ul id="nav" class="dropdown dropdown-horizontal">';
    }
//            echo "<pre>";
//    print_r($tree);exit();
    foreach($tree as $key => $value) {
        $liClass = '';
        if(!isset($value['display']) || $value['display']!=1){
            $liClass = 'hidden-menu';
        }
//        if($value['title'] == 'Test 2'){
//            echo "<pre>";
//            print_r($value);exit();
//        }

        if(isset($value['children']) && is_array($value['children']) && !empty($value['children'])){
            $anchor = $value['title'];
            if(!in_array($value['link'],$noAnchorLink)){
                $anchor = anchor($value['link'],$value['title']);
            }

            if(isset($tree[($key+1)])){//Jika ada node setelahnya
                $out.= '<li class="dir">';
                $out.=  $anchor. olLiMenuTree($value['children']);
                $out.= '</li>';
            }else{
                $out.= '<li class="dir">';
                $out.=  $anchor. olLiMenuTree($value['children']);
                $out.= '</li>';
                if(is_null($username)){
                    $out.= '<li class="last"></li>';
                }
            }

        }else {
            if( $key==0 && isset($tree[($key+1)])){//Jika node pertama dan ada node setelahnya
                $out.= '<li class="first '.$liClass.'">';
            }elseif(isset($tree[($key+1)])){//Jika ada node setelahnya
                $out.= '<li class="'.$liClass.'">';
            }else{
                $out.= '<li class="last '.$liClass.'">';
            }
            $anchor = $value['title'];
            if(!in_array($value['link'],$noAnchorLink)){
                $anchor = anchor($value['link'],$value['title']);
            }
            $out.= $anchor;
            $out.= '</li>';
        }

    }

    if(!is_null($username)){//Hanya untuk menu paling atas
        $out .= '<li class="last"><a href="'.site_url('login/logoff').'">Logoff sebagai '. $username.'</a></li>';
    }

    $out.= '</ul>';
    return $out;
}