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

$client = new CurseClient();
$data = $client->getMinecraftVersions();
$data = $client->getLatestMinecraft();

$data = $client->testGet($data->url);
print_r($data);
exit;

//$data = $client->testGet('https://minecraft.curseforge.com/api/projects/energysynergy/relations/dependencies');
//$data = $client->getDependencies();
//print_r($data);
//$data = $client->getPackageVersions("smeltcycle");//chisel");

$url = "https://api.twitch.tv/helix/games?name=minecraft";
$data = $client->testGet($url);

print_r($data);
echo "le chisel";