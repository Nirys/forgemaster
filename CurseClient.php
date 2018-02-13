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
    protected $_baseUrl = "https://minecraft.curseforge.com/api/";
    protected $_mcVersions = 'https://launchermeta.mojang.com/mc/game/version_manifest.json';

    public function getLatestMinecraft($forceFlush = false){
        $versions = $this->getMinecraftVersions();
        $release = $versions->latest->release;
        foreach($versions->versions as $key=>$version){
            if($version->id==$release) return $version;
        }

        return null;
    }


    public function getMinecraftVersions($forceFlush = false){
        $data = json_decode($this->curlGet($this->_mcVersions, $forceFlush));
        return $data;
    }

    public function getVersions(){
        $data = json_decode($this->curlGet($this->_baseUrl . 'game/versions'));
        return $data;
    }

    public function getDependencies(){
        $data = $this->curlGet($this->_baseUrl . 'game/dependencies');
        return $data;
    }

    public function getPackageVersions($package){
        return $this->curlGet($this->_baseUrl . 'projects/' . $package . '/versions');
    }

    public function testGet($url){
        return $this->curlGet($url);

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