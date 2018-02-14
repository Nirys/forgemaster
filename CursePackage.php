<?php
/**
 * Created by PhpStorm.
 * User: kath.young
 * Date: 2/14/18
 * Time: 12:39 PM
 */
namespace Forgemaster;

class CursePackage {
    protected $_isLoaded = false;
    protected $_description;
    protected $_slug;
    protected $_name;
    protected $_author;
    protected $_dependencies = null;

    public static function load($packageId){
        $data = CurseClient::instance()->abstractGet("projects/$packageId");
        $doc = CurseDOM::fromHTML($data);
        $item = $doc->query("//div[@class='project-description']")->item(0);
        $description = $item->ownerDocument->saveHTML($item);

        $name = $doc->query("//meta[@property='og:title']")->item(0)->getAttribute('content');
        $author = '';

        $pkg = new self($packageId, $name, $author, $description);
        return $pkg;
    }

    public function __construct($slug, $name, $author, $description){
        $this->_slug = $slug;
        $this->_name = $name;
        $this->_author = $author;
        $this->_description = $description;
        $this->_isLoaded = true;
    }

    public function getDependencies($forceFlush = false){
        if($this->_dependencies===null || $forceFlush){
            $url = "projects/" . $this->_slug . "/relations/dependencies?filter-related-dependencies=3";
            $data = CurseClient::instance()->abstractGet($url);
            print_r($data);
            die;
        }
        return $this->_dependencies;
    }
}