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

$client = new CurseClient();
//$data = $client->getModCategories();
$data = $client->searchMods("ender-io");

$slug = $data[0]['slug'];
echo "Get slug $slug\n";
$pkg = CursePackage::load($data[0]['slug']);
$deps = $pkg->getDependencies();
print_r($deps);
exit;

//$data = $client->testGet('https://minecraft.curseforge.com/api/projects/energysynergy/relations/dependencies');
//$data = $client->getDependencies();
//print_r($data);
//$data = $client->getPackageVersions("smeltcycle");//chisel");

$url = "https://api.twitch.tv/helix/games?name=minecraft";
$data = $client->testGet($url);

print_r($data);
echo "le chisel";