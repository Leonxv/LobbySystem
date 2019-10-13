<?php

namespace LobbyItems\Items;

use FormAPI\FormAPI;
use pocketmine\Server;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use LobbyItems\Main;

class NewsItem extends Item{
    public $plugin;

    public function __construct(Main $plugin) {
        parent::__construct(self::NETHER_STAR, 0, "Hide");
        $this->setCustomName("§r§c§lSpieler Verstecken");
        $this->plugin = $plugin;
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool {
        if(!$player->hasItemCooldown($this)){

            $this->useItem($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        if(!$player->hasItemCooldown($this)){

            Server::getInstance()->dispatchCommand($player, "hide");

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function getCooldownTicks(): int {
        return 20;
    }
}
