<?php
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;

use \Spirit55555\Minecraft\MinecraftColors;
use \Spirit55555\Minecraft\MinecraftJSONColors;

function ping_minecraft($address)
{
    $host = '';
    $port = 25565;

    // parse address
    $address = explode(':', $address);
    if (count($address) == 2) {
        $host = $address[0];
        $port = $address[1];
    } else {
        $host = $address[0];
    }
    try {
        $query = new MinecraftPing($host, $port);

        return $query->Query();
    } catch (MinecraftPingException $e) {
        
    } finally {
        if ($query) {
            $query->Close();
        }
    }

    return false;
}

function color_code_to_html($raw) 
{
    return MinecraftColors::convertToHTML($raw,true);
}

function color_code_clean($raw) 
{
    return MinecraftColors::clean($raw);
}

function json_to_color_code($raw) 
{
    return MinecraftJSONColors::convertToLegacy($raw);
}