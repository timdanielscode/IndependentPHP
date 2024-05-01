<?php

namespace app\controllers;

class Controller {

    private $_path;

    /**
     * To set the view path
     * 
     * @param string $path name view
     * @return object Controller
     */
    public function view($path) {

        if(!empty($path) && $path !== null) {

            $this->_path = $path;
        } 

        return $this;
    }    

    /**
     * To extract data on view and include the view on setted path
     * 
     * @param array $args optional view variables
     */
    public function data($args = null) {

        if(!empty($args) && $args !== null) {

            extract($args);
        }

        include("../app/views/" . $this->_path . ".php");
    }

    /**
     * To include views from within the includes folder
     * 
     * @param string $file name view
     */
    public function include($file) {

        require "../app/views/includes/" . $file . ".php";
    }

    /**
     * To include a meta title on view
     * 
     * @param string $title meta
     */
    public function title($title) {

        echo "<title>$title</title>";
    }

    /**
     * To include a meta description on a view
     * 
     * @param string $content meta 
     */
    public function description($content) {

        echo '<meta name="description" content="'.$content.'">';
    }

    /**
     * To include a meta tag on a view
     * 
     * @param string $name meta
     * @param string $content meta
     */
    public function meta($name, $content) {

        echo '<meta name="'.$name.'" content="'.$content.'">';
    } 

    /**
     * To include a js script on a view
     * 
     * @param string $src source
     * @param string $defer optional
     */
    public function script($src, $defer = null) {

        if($defer === true) {

            echo '<script type="text/javascript" src='.'"'.$src.'"'.' defer></script>';
        } else {

            echo '<script type="text/javascript" src='.'"'.$src.'"'.'></script>';
        }
    }

    /**
     * To link a css file on a view
     * 
     * @param string $href hypertext reference
     */
    public function stylesheet($href) {

        echo '<link rel='.'"'.'stylesheet'.'" '. 'type='.'"'.'text/css'.'" '. 'href='.'"'.$href.'"'.'>';
    }
}