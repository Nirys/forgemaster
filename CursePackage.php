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
    protected $_requiredDependencies = null;

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
            $doc = CurseClient::instance()->getDOM("projects/" . $this->_slug . "/relations/dependencies", $forceFlush);
            $this->_dependencies = $this->parseDependencies($doc);
        }
        return $this->_dependencies;
    }


    public function getRequiredDependencies($forceFlush = false){
        if($this->_requiredDependencies===null || $forceFlush){
            $doc = CurseClient::instance()->getDOM("projects/" . $this->_slug . "/relations/dependencies?filter-related-dependencies=3", $forceFlush);
            $this->_requiredDependencies = $this->parseDependencies($doc);
        }
        return $this->_requiredDependencies;
    }

    protected function parseDependencies($doc){
        $data = $doc->query("//li[@class='project-list-item']/div[@class='details']//div[@class='info name']//div[@class='name-wrapper overflow-tip']/a");
        $deps = [];
        for($i=0; $i < $data->length; $i++){
            $node = $data->item($i);
            $name = $node->nodeValue;
            $slug = $node->getAttribute('href');
            $slug = substr($slug, strrpos($slug, "/")+1, strlen($slug));
            $deps[] = array(
                'name' => $name,
                'slug' => $slug
            );
        }
        return $deps;
    }
}