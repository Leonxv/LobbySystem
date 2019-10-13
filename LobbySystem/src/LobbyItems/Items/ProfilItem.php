<?php

namespace LobbyItems\Items;

use FormAPI\FormAPI;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use LobbyItems\Main;
use pocketmine\Server;
use pocketmine\utils\Config;

class ProfilItem extends Item{
    public $plugin;

    public function __construct(Main $plugin) {
        parent::__construct(self::SKULL, 3, "Profil");
        $this->setCustomName("§r§c§lProfil");
        $this->plugin = $plugin;
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool {
        if(!$player->hasItemCooldown($this)){

            $this->onProfil($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        if(!$player->hasItemCooldown($this)){

            $this->onProfil($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function onProfil(Player $player) : void{
        $api = FormAPI::getInstance();
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            if($result === 0) {
               $this->bwStats($player);
            }
            if($result === 1) {
                $this->onMLGStats($player);
            }
            if($result === 2) {
                $this->bffaStats($player);
            }
        });
        $form->setTitle("§l§cProfil");
        $form->setContent("§7Sehe Dein Profil");
        $form->addButton("§cBedWars Stats");
        $form->addButton("§cMLGRush Stats");
        $form->addButton("§cBuildFFA Stats");
        $form->sendToPlayer($player);
    }

    public function getCooldownTicks(): int {
        return 20;
    }

    public function bwStats(Player $player)
    {
        if (file_exists("/root/Server/Games/BedWars/players/" . $player->getName() . ".yml")) {
            $pf = new Config("/root/Server/Games/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $pcoins = $coins->get("coins");
            $deaths = $pf->get("Deaths");
            $kills = $pf->get("Kills");
            $wins = $pf->get("Wins");
            $api = FormAPI::getInstance();
            $form = $api->createSimpleForm(function (Player $sender, $data) {
                $result = $data[0];
                if ($result === null) {
                    return true;
                }
                switch ($result) {
                    case 0:
                        break;
                }
            });
            $form->setTitle("§c§lSTATS");
            $form->setContent("§cDeine Stats:\n\n§cDeine Kills: §7$kills\n\n§cDeine Tode: §7$deaths\n\n§cDeine Wins: §7$wins\n\n§cDeine Coins: §7$pcoins");
            $form->addButton("§7Zurück");
            $form->sendToPlayer($player);
        }
    }

    public function bffaStats(Player $player)
    {
        if (file_exists("/cloud/users/" . $player->getName() . ".yml")) {
            $pf = new Config("/cloud/users/" . $player->getName() . ".yml", Config::YAML);
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $pcoins = $coins->get("coins");
            $bkills = $pf->get("bkills");
            $belo = $pf->get("elo");
            $btode = $pf->get("btode");
            $api = FormAPI::getInstance();
            $form = $api->createSimpleForm(function (Player $sender, $data) {
                $result = $data[0];
                if ($result === null) {
                    return true;
                }
                switch ($result) {
                    case 0:
                        break;
                }
            });
            $form->setTitle("§c§lSTATS");
            $form->setContent("§cDeine Stats:\n\n§cDeine Kills: §7$bkills\n\n§cDeine Tode: §7$btode\n\n§cDeine Elo: §7$belo\n\n§cDeine Coins: §7$pcoins");
            $form->addButton("§7Zurück");
            $form->sendToPlayer($player);
        }
    }
    public function onMLGStats(Player $player)
    {
        if (file_exists("/home/Datenbank/stats/mlgrush/" . $player->getName() . ".yml")) {
            $pf = new Config("/home/Datenbank/stats/mlgrush/" . $player->getName() . ".yml", Config::YAML);
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $pcoins = $coins->get("coins");
            $wins = $pf->get("wins");
            $defeats = $pf->get("defeats");
            $api = FormAPI::getInstance();
            $form = $api->createSimpleForm(function (Player $sender, $data) {
                $result = $data[0];
                if ($result === null) {
                    return true;
                }
                switch ($result) {
                    case 0:
                        break;
                }
            });
            $form->setTitle("§c§lSTATS");
            $form->setContent("§cDeine Stats:\n\n§cDeine Wins: §7$wins\n\n§cDeine Niederlagen: §7$defeats\n\n§cDeine Coins: §7$pcoins");
            $form->addButton("§7Zurück");
            $form->sendToPlayer($player);
        }
    }
}
