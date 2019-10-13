<?php

namespace LobbyItems;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\Level;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\WaterParticle;

class ItemsListener implements Listener{
    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $this->plugin->mainItems($player);
    }

    public function onMove(PlayerMoveEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();

        if(isset($this->plugin->particles[$name])){
            $particle = $this->plugin->particles[$name];
            if($particle === "ender"){
                $player->getLevel()->addParticle(new PortalParticle($player));
            }elseif($particle === "fire"){
                $player->getLevel()->addParticle(new FlameParticle($player));
            }elseif($particle === "water"){
                $player->getLevel()->addParticle(new WaterParticle($player->add(0, 1, 0)));
            }elseif($particle === "magic"){
                $player->getLevel()->addParticle(new EnchantmentTableParticle($player));
            }elseif($particle === "heart"){
                $player->getLevel()->addParticle(new HeartParticle($player));
            }
        }
    }
}
