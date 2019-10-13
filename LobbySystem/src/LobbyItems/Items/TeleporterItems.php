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
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use pocketmine\utils\Binary;
use pocketmine\utils\MainLogger;

class TeleporterItems extends Item{
    public $plugin;

    public function __construct(Main $plugin) {
        parent::__construct(self::COMPASS, 0, "Teleporter");
        $this->setCustomName("§r§c§lTeleporter");
        $this->plugin = $plugin;
    }
    
    public static function transfer(Player $player, String $server): bool
    {

        $pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungeecord:main";
        $pk->eventData = Binary::writeShort(strlen("Connect")) . "Connect" . Binary::writeShort(strlen($server)) . $server;
        $player->sendDataPacket($pk);
        return true;
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): bool {
        if(!$player->hasItemCooldown($this)){

            $this->useItem($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }

    public function onClickAir(Player $player, Vector3 $directionVector) : bool {
        if(!$player->hasItemCooldown($this)){

            $this->useItem($player);

            $player->resetItemCooldown($this);
        }
        return false;
    }
    
    public function FFA(Player $player) : void{
        $api = FormAPI::getInstance();
        $bw1 = new Config("/root/Server/Counter/FFA/BuildFFA/counter.yml", Config::YAML);
        $bw2 = new Config("/root/Server/Counter/FFA/FFA/counter.yml", Config::YAML);
        $bw3 = new Config("/root/Server/Counter/FFA/ComboFFA/counter.yml", Config::YAML);
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            $bw1 = new Config("/root/Server/Counter/FFA/BuildFFA/counter.yml", Config::YAML);
            if($result === 0) {
                if ($bw1->get("Status") == "§aOnline") {
                    $this->transfer($player, "buildffa-1");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw1->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
            $bw2 = new Config("/root/Server/Counter/FFA/FFA/counter.yml", Config::YAML);
            if($result === 1) {
                if ($bw2->get("Status") == "§aOnline") {
                    $this->transfer($player, "ffa-1");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw2->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
            $bw3 = new Config("/root/Server/Counter/FFA/ComboFFA/counter.yml", Config::YAML);
            if($result === 2) {
                if ($bw3->get("Status") == "§aOnline") {
                    $this->transfer($player, "comboffa-1");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw3->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
        });
        $bw1 = new Config("/root/Server/Counter/FFA/BuildFFA/counter.yml", Config::YAML);
        $form->setTitle("§l§cTeleporter");
        $form->setContent("§7Wähle einen Server aus!");
        $form->addButton("§l§cBuildFFA\n§r§f" . $bw1->get("Lobby_Count") . "§7/§f50§8 | " . $bw1->get("Status"));
        $form->addButton("§l§cFFA\n§r§f" . $bw2->get("Lobby_Count") . "§7/§f50§8 | " . $bw2->get("Status"));
        $form->addButton("§l§cComboFFA\n§r§f" . $bw3->get("Lobby_Count") . "§7/§f50§8 | " . $bw3->get("Status"));
        $form->sendToPlayer($player);
    }
    
    public function Bedwars(Player $player) : void{
        $api = FormAPI::getInstance();
        $bw1 = new Config("/root/Server/Counter/BedWars/BW2x1-1/counter.yml", Config::YAML);
        $bw2 = new Config("/root/Server/Counter/BedWars/BW2x1-2/counter.yml", Config::YAML);
        $bw3 = new Config("/root/Server/Counter/BedWars/BW2x1-3/counter.yml", Config::YAML);
        $bw4 = new Config("/root/Server/Counter/BedWars/BW2x1-4/counter.yml", Config::YAML);
        $bw5 = new Config("/root/Server/Counter/BedWars/BW2x1-5/counter.yml", Config::YAML);
        $bw6 = new Config("/root/Server/Counter/BedWars/BW2x1-6/counter.yml", Config::YAML);
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            $bw1 = new Config("/root/Server/Counter/BedWars/BW2x1-1/counter.yml", Config::YAML);
            if($result === 0) {
                if ($bw1->get("Status") == "§aOnline") {
                    $this->transfer($player, "bw2x1-1");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw1->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
            $bw2 = new Config("/root/Server/Counter/BedWars/BW2x1-2/counter.yml", Config::YAML);
            if($result === 1) {
                if ($bw2->get("Status") == "§aOnline") {
                    $this->transfer($player, "bw2x1-2");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw2->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }

            $bw3 = new Config("/root/Server/Counter/BedWars/BW2x1-3/counter.yml", Config::YAML);
            if($result === 2) {
                if ($bw3->get("Status") == "§aOnline") {
                    $this->transfer($player, "bw2x1-3");
                } else {
                   $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw3->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            } 

            $bw4 = new Config("/root/Server/Counter/BedWars/BW2x1-4/counter.yml", Config::YAML);
            if($result === 3) {
                if ($bw4->get("Status") == "§aOnline") {
                    $this->transfer($player, "bw2x1-4");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw4->get("Lobby_Count") == 50) {
               $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
            $bw5 = new Config("/root/Server/Counter/BedWars/BW2x1-5/counter.yml", Config::YAML);
            if($result === 4) {
                if ($bw5->get("Status") == "§aOnline") {
                    $this->transfer($player, "bw2x1-5");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw5->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
            $bw6 = new Config("/root/Server/Counter/BedWars/BW2x1-6/counter.yml", Config::YAML);
            if($result === 5) {
                if ($bw6->get("Status") == "§aOnline") {
                    $this->transfer($player, "bw2x1-6");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw6->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
        });
        $bw1 = new Config("/root/Server/Counter/BedWars/BW2x1-1/counter.yml", Config::YAML);
        $form->setTitle("§l§cTeleporter");
        $form->setContent("§7Wähle einen Server aus!");
        $form->addButton("§l§cBW2x1-1\n§r§f" . $bw1->get("Lobby_Count") . "§r§7/§f2§r§8 | §r" . $bw1->get("Status"));
        $form->addButton("§l§cBW2x1-2\n§r§f" . $bw2->get("Lobby_Count") . "§r§7/§f2§r§8 | §r" . $bw2->get("Status"));
        $form->addButton("§l§cBW2x1-3\n§r§f" . $bw3->get("Lobby_Count") . "§r§7/§f2§r§8 | §r" . $bw3->get("Status"));
        $form->addButton("§l§cBW2x1-4\n§r§f" . $bw4->get("Lobby_Count") . "§r§7/§f2§r§8 | §r" . $bw4->get("Status"));
        $form->addButton("§l§cBW2x1-5\n§r§f" . $bw5->get("Lobby_Count") . "§r§7/§f2§r§8 | §r" . $bw5->get("Status"));
        $form->addButton("§l§cBW2x1-6\n§r§f" . $bw6->get("Lobby_Count") . "§r§7/§f2§r§8 | §r" . $bw6->get("Status"));
        $form->sendToPlayer($player);
    }


    public function MLGRush(Player $player) : void{
        $api = FormAPI::getInstance();
        $bw1 = new Config("/root/Server/Counter/MLGRush/MR1/counter.yml", Config::YAML);
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            $bw1 = new Config("/root/Server/Counter/MLGRush/MR1/counter.yml", Config::YAML);
            if($result === 0){
                if ($bw1->get("Status") == "§aOnline"){
                    $this->transfer($player, "mlgrush-1");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw1->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");;
            }
        });
        $bw1 = new Config("/root/Server/Counter/MLGRush/MR1/counter.yml", Config::YAML);
        $form->setTitle("§l§cTeleporter");
        $form->setContent("§7Wähle einen Server aus!");
        $form->addButton("§l§cMLGRush-1\n§f" . $bw1->get("Lobby_Count") . "§7/§f50§8 | " . $bw1->get("Status"));
        $form->sendToPlayer($player);
    }

    public function CityBuild(Player $player) : void{
        $api = FormAPI::getInstance();
        $bw1 = new Config("/root/Server/Counter/CB/CB-1/counter.yml", Config::YAML);
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            $bw1 = new Config("/root/Server/Counter/CB/CB-1/counter.yml", Config::YAML);
            if($result === 0){
                if ($bw1->get("Status") == "§aOnline"){
                $this->transfer($player, "citybuild-1");
                } else {
                    $player->sendMessage($this->plugin->prefix . "§cDieser Server ist derzeit Offline§7!");
                }
            }elseif ($bw1->get("Lobby_Count") == 50) {
                $player->sendMessage($this->plugin->prefix . "§cDieser Server ist bereits Voll§7!");
            }
        });
        $bw1 = new Config("/root/Server/Counter/CB/CB-1/counter.yml", Config::YAML);
        $form->setTitle("§l§cTeleporter");
        $form->setContent("§7Wähle einen Server aus!");
        $form->addButton("§l§cCityBuild\n§r§f" . $bw1->get("Lobby_Count") . "§r§7/§f50§r§8 | §r" . $bw1->get("Status"));
        $form->sendToPlayer($player);
    }

    public function Teleporter(Player $player) : void{
        $api = FormAPI::getInstance();
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result === null){
                return;
            }
            if($result === 0){
                $this->Bedwars($player);
            }
            if($result === 1){
                $this->FFA($player);
            }
            if($result === 2){
                $this->CityBuild($player);
            }
            if($result === 3){
                $this->MLGRush($player);
            }
        });
        $form->setTitle("§c§lTeleporter");
        $form->setContent("§7Wähle einen SpielModus aus!");
        $form->addButton("§l§4Bed§cWars");
        $form->addButton("§l§cFFA§8-§4Games");
        $form->addButton("§l§4City§cBuild");
        $form->addButton("§l§cMLG§4Rush");
        $form->sendToPlayer($player);
    }
    
    public function useItem(Player $player) : void{
        $this->Teleporter($player);
    }

    public function getCooldownTicks(): int {
        return 20;
    }
}
