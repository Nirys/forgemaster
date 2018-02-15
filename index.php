<?php
/**
 * Created by PhpStorm.
 * User: kath.young
 * Date: 2/13/18
 * Time: 12:00 PM
 */
namespace Forgemaster;

require_once 'CurlCache.php';
require_once 'CurseClient.php';
require_once 'CursePackage.php';
require_once 'CurseDOM.php';

$MCVersion = '1.7.4';

$Mods = array('ender-io','chisel');

$client = new CurseClient();
$results = $client->searchMods('applied energistics');
print_r($results);
die;
//$data = $client->getModCategories();
$ver = $client->getMinecraftVersions();
$types = array();
foreach($ver as $version){
    if(!in_array($version->type, $types)) $types[] = $version->type;
    if($version->type=='release' || $version->type=='snapshot'){
        if($version->type !== 'release') echo '* ';
        echo $version->id . "\n";
    }
}
exit;

//$data = $client->testGet('https://minecraft.curseforge.com/api/projects/energysynergy/relations/dependencies');
//$data = $client->getDependencies();
//print_r($data);
//$data = $client->getPackageVersions("smeltcycle");//chisel");
