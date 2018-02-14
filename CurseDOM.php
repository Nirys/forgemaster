<?php
/**
 * Created by PhpStorm.
 * User: kath.young
 * Date: 2/14/18
 * Time: 1:05 PM
 */

namespace Forgemaster;


class CurseDOM extends \DOMDocument {
    protected $_xpath = null;

    public static function fromHTML($html){
        $item = new self();
        @$item->loadHTML($html);
        return $item;
    }

    /**
     * @param $query
     * @return \DOMNodeList
     */
    public function query($query){
        if($this->_xpath===null){
            $this->_xpath = new \DOMXPath($this);
        }
        $nlist = $this->_xpath->query($query);
        return $nlist;
    }

}