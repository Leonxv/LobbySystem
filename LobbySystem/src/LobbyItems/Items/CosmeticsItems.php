<?php

namespace LobbyItems\Items;

use FormAPI\FormAPI;
use pocketmine\Server;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use LobbyItems\Main;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class CosmeticsItems extends Item{
    public $plugin;

    public function __construct(Main $plugin) {
        parent::__construct(self::ENDER_CHEST, 0, "Gadgets");
        $this->setCustomName("§r§c§lGadgets");
        $this->plugin = $plugin;
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool {
        if(!$player->hasItemCooldown($this)){

            $this->Features($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        if(!$player->hasItemCooldown($this)){

            $this->Features($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function Features(Player $player) : void{
        $api = FormAPI::getInstance();
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            if($result === 0){
                if($player->hasPermission("lobby.vip")) {
                    $this->Parti($player);
                }else {
                    $player->sendMessage($this->plugin->prefix . "§cDu hast keine Rechte!");
                }
            }
            if($result === 1){
                if ($player->hasPermission("group.nick")) {
                    Server::getInstance()->dispatchCommand($player, "nick");
                }else{
                    $player->sendMessage($this->plugin->prefix . "§cDu hast keine Rechte!");
                }
            }
            if($result === 2) {
                $this->branks($player);
            }
        });
        $form->setTitle("§l§cGADGETS");
        $form->setContent("§7Suche aus was du machen willst!");
        $form->addButton("§l§cPartikel");
        $form->addButton("§l§cNick");
        $form->addButton("§l§cRangShop");
        $form->sendToPlayer($player);
    }

    public function branks(Player $player) : void{
        $api = FormAPI::getInstance();
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return;
            }
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $rcfg = new Config("/cloud/Group/player/" . $player->getName() . ".yml", Config::YAML);
            if ($result === 0) {
                if ($coins->get("coins") >= 35000) {
                    if (!$player->hasPermission("lobby.rainbow") or !$player->hasPermission("lobby.team")) {
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "remcoins " . $player->getName() . " 35000");
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "group set " . $player->getName() . " Rainbow");
                       }
                    } else {
                        $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genug Coins um dir den §c§lR§2a§3i§4n§5b§6o§9w §r§cRang kaufen zu können!");
                    }
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDu hast bereits einen höheren Rang!");
                }
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $rcfg = new Config("/cloud/Group/player/" . $player->getName() . ".yml", Config::YAML);
            if ($result === 1) {
                if ($coins->get("coins") >= 25000) {
                    if (!$player->hasPermission("lobby.rusher") or !$player->hasPermission("lobby.team")) {
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "remcoins " . $player->getName() . " 10000");
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "group set " . $player->getName() . " Rusher");
                    }
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genug Coins um dir den §c§lRUS§4HER §r§cRang kaufen zu können!");
                }
            } else {
                $player->sendMessage($this->plugin->prefix . "§cDu hast bereits einen höheren Rang!");
            }
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $rcfg = new Config("/cloud/Group/player/" . $player->getName() . ".yml", Config::YAML);
            if ($result === 2) {
                if ($coins->get("coins") >= 5000) {
                    if (!$player->hasPermission("lobby.vip") or !$player->hasPermission("lobby.team")) {
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "remcoins " . $player->getName() . " 5000");
                        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), "group give " . $player->getName() . " VIP");
                        }
                    } else {
                        $player->sendMessage($this->plugin->prefix . "§cDu hast nicht genug Coins um einen Rang kaufen zu können!");
                    }
                } else {
                     $player->sendMessage($this->plugin->prefix . "§cDu hast bereits einen Höheren Rang!");
                }
        });
        $form->setTitle("§l§cRANG §4§lSHOP");
        $form->setContent("§7Kaufe dir Ränge mit Ingame Coins!");
        $form->addButton("§c§lR§2a§3i§4n§5b§6o§9w\n §r§a35.000 §eCoins");
        $form->addButton("§c§lRus§4§lher\n §r§a10.000 §eCoins");
        $form->addButton("§e§lVIP\n §r§a5000 §eCoins");
        $form->sendToPlayer($player);
    }

    public function Parti(Player $player){

        if(!$player->hasPermission("lobby.vip")){
            $player->sendMessage($this->plugin->prefix . "§cKeine Rechte!");
            return;
        }

        $form = FormAPI::getInstance()->createSimpleForm();

        $form->setTitle("§c§lPARTIKEL");

        $form->addButton("Ender");
        $form->addButton("Fire");
        $form->addButton("Water");
        $form->addButton("Magic");
        $form->addButton("Heart");

        $pl = $this->plugin;

        $form->setCallable(function (Player $player, $res)use($pl){
            if(is_null($res)){
                return;
            }
            $name = $player->getName();
            if($res == 0){
                $pl->particles[$name] = "ender";
            }elseif($res == 1){
                $pl->particles[$name] = "fire";
            }elseif($res == 2){
                $pl->particles[$name] = "water";
            }elseif($res == 3){
                $pl->particles[$name] = "magic";
            }elseif($res == 4){
                $pl->particles[$name] = "heart";
            }
        });

        $form->sendToPlayer($player);
    }

    public function getCooldownTicks(): int {
        return 20;
    }
}
