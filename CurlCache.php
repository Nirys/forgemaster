<?php
/**
 * Created by PhpStorm.
 * User: kath.young
 * Date: 2/13/18
 * Time: 1:16 PM
 */

namespace Forgemaster;


class CurlCache
{
    protected static function sanitizeUrl($url){
        $file = str_replace(":","-", $url);
        $file = str_replace("/","-", $file);
        $file = str_replace("&", "-", $file);
        $file = str_replace("+", "_", $file);

        return $file;
    }

    public static function get($url){
        $file = self::sanitizeUrl($url);

        if(file_exists("cache/$file")){
            return file_get_contents("cache/$file");
        }else{
            return null;
        }
    }

    public static function put($url, $data){
        $file = self::sanitizeUrl($url);

        if(!file_exists("cache")){
            mkdir("cache");
        }

        file_put_contents("cache/$file", $data);
    }
}