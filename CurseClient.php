<?php
/**
 * Created by PhpStorm.
 * User: kath.young
 * Date: 2/13/18
 * Time: 12:00 PM
 */

namespace Forgemaster;


class CurseClient
{
    protected $_token = '2e251f73-64dd-4fd5-aaf2-53c1a862b836';
    protected $_baseUrl = "https://minecraft.curseforge.com/";
    protected $_mcVersions = 'https://launchermeta.mojang.com/mc/game/version_manifest.json';
    protected static $_instance = null;

    public static function instance(){
        if(self::$_instance===null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getLatestMinecraft($forceFlush = false){
        $versions = $this->getMinecraftVersions();
        $release = $versions->latest->release;
        foreach($versions->versions as $key=>$version){
            if($version->id==$release) return $version;
        }

        return null;
    }

    public function getModCategories($forceFlush = false){
        $data = $this->curlGet($this->_baseUrl . "mc-mods", $forceFlush);
        print_r($data);
    }

    public function searchMods($query, $forceFlush = false){
        $url = $this->_baseUrl . "search?search=" . urlencode($query);
        $result = $this->curlGet($url);

        $result = str_replace("\r\n","", $result);
        preg_match_all("/results-name[^<]+<[^\"]+\"([^\"]+)\">([^<]+)<.+?\"results-summary\">([^<]+)</", $result, $matches);

        $data = array();
        foreach($matches[1] as $key => $url){
            preg_match("/projects\/([^\?]+?)\?.+projectID=(.+)/", $url, $matched);
            $projectId = $matched[2];
            $slug = $matched[1];

            $data[]= array(
                'url' => $url,
                'projectId' => $projectId,
                'slug' => $slug,
                'name' => $matches[2][$key],
                'description' => ltrim(rtrim($matches[3][$key]))
            );
        }
        return $data;
    }


    public function getMinecraftVersions($forceFlush = false){
        $data = json_decode($this->curlGet($this->_mcVersions, $forceFlush));
        return $data->versions;
    }

    public function getVersions(){
        $data = json_decode($this->curlGet($this->_baseUrl . 'api/game/versions'));
        return $data;
    }

    public function getDependencies(){
        $data = $this->curlGet($this->_baseUrl . 'api/game/dependencies');
        return $data;
    }

    public function getPackageVersions($package){
        return $this->curlGet($this->_baseUrl . 'api/projects/' . $package . '/versions');
    }

    public function testGet($url){
        return $this->curlGet($url);

    }

    public function getDOM($url, $forceFlush = false){
        return CurseDOM::fromHTML($this->abstractGet($url, $forceFlush));
    }

    public function abstractGet($url, $forceFlush = false){
        return $this->curlGet($this->_baseUrl . $url, $forceFlush);
    }

    protected function curlGet($url, $forceFlush = false){
        $data = CurlCache::get($url);

        if($data && !$forceFlush) return $data;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Api-Token: ' . $this->_token,
            'Accepts: application/json'
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        CurlCache::put($url, $output);
        return $output;
    }
}