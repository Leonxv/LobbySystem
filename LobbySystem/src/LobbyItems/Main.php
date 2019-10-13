<?php

namespace LobbyItems;

use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use LobbyItems\Items\CosmeticsItems;
use LobbyItems\Items\ProfilItem;
use LobbyItems\Items\NewsItem;
use LobbyItems\Items\TeleporterItems;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\utils\TextFormat;
use pocketmine\utils\TextFormat as f;
use LobbyItems\Tasks\ScoreBoardTask;
use FormAPI\FormAPI;

class Main extends PluginBase implements Listener {

    public $cfg;

    public $news = "";

    public $items = [];

    public $teleporter = [];

    public $particles = [];
	
	public $prefix = "§cRush§4Unity §8» §7";

    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        FormAPI::enable($this);

        $this->initItems();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new ItemsListener($this), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ScoreBoardTask($this), 200);

    }

    public function setScoreboardEntry(Player $player, int $score, string $msg, string $objName)
    {
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $objName;
        $entry->type = 3;
        $entry->customName = " $msg   ";
        $entry->score = $score;
        $entry->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = 0;
        $pk->entries[$score] = $entry;
        $player->sendDataPacket($pk);
    }

    public function rmScoreboardEntry(Player $player, int $score)
    {
        $pk = new SetScorePacket();
        if (isset($pk->entries[$score])) {
            unset($pk->entries[$score]);
            $player->sendDataPacket($pk);
        }
    }

    public function createScoreboard(Player $player, string $title, string $objName, string $slot = "sidebar", $order = 0)
    {
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = $slot;
        $pk->objectiveName = $objName;
        $pk->displayName = $title;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = $order;
        $player->sendDataPacket($pk);
    }

    public function rmScoreboard(Player $player, string $objName)
    {
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $objName;
        $player->sendDataPacket($pk);
    }

    public function onScore()
    {
        $pl = $this->getServer()->getOnlinePlayers();
        foreach ($pl as $player) {
            $coins = new Config("/root/Server/Core/Coins/" . $player->getName() . ".yml", Config::YAML);
            $pcoins = $coins->get("coins");
            $name = $player->getName();
            $this->rmScoreboard($player, "objektName");
            $this->createScoreboard($player, "§c§lRUSH§4§lUNITY", "objektName");
            $this->setScoreboardEntry($player, 1, " ", "objektName");
            $this->setScoreboardEntry($player, 2, f::RED . "§7§l» §c§lServer", "objektName");
            $this->setScoreboardEntry($player, 3, f::GREEN . "§8➥ §7Lobby", "objektName");
            $this->setScoreboardEntry($player, 4, "  ", "objektName");
            $this->setScoreboardEntry($player, 5, f::YELLOW . "§7§l» §c§lOnline", "objektName");
            $this->setScoreboardEntry($player, 6, f::RED . "§8➥ §c" . count($this->getServer()->getOnlinePlayers()) . "§7/§c{$this->getServer()->getMaxPlayers()}", "objektName");
			$this->setScoreboardEntry($player, 7, "   ", "objektName");
            $this->setScoreboardEntry($player, 8, f::DARK_PURPLE . "§7§l» §c§lCoins", "objektName");
            $this->setScoreboardEntry($player, 9, f::RED . "§8➥ §7$pcoins", "objektName");
			$this->setScoreboardEntry($player, 10, "    ", "objektName");
			$this->setScoreboardEntry($player, 11, f::AQUA . "§7§l» §c§lName", "objektName");
            $this->setScoreboardEntry($player, 12, f::DARK_RED . "§8➥ §c" . $player->getDisplayName(), "objektName");
        }
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
		$player->setGamemode(0);
        $player->setHealth(20);
        $player->setFood(20);
        $event->setJoinMessage("");
        $player->addTitle("§c§lRUSH§4§lUNITY");
        $player->addSubTitle("§cViel Spaß!");
        }

    public function onQuit(PlayerQuitEvent $event) {
        $event->setQuitMessage("");
    }

    public function initItems() {
        $this->items["teleporter"] = new TeleporterItems($this);
        $this->items["cosmetics"] = new CosmeticsItems($this);
        $this->items["hide"] = new NewsItem($this);
        $this->items["profil"] = new ProfilItem($this);
    }

    public function mainItems(Player $player) {
        $inv = $player->getInventory();

        $inv->clearAll();

        $inv->setItem(0, $this->items["teleporter"]);
        $inv->setItem(2, $this->items["cosmetics"]);
        $inv->setItem(4, $this->items["hide"]);
        $inv->setItem(8, $this->items["profil"]);
    }
    
    public function onDmg(EntityDamageEvent $event) {
    	$event->setCancelled(true);
    }
    
    public function onPlace(BlockPlaceEvent $event) {
    	$player = $event->getPlayer();
         if($player->hasPermission("lobby.build")) {
         	$event->setCancelled(false);
         }else{
         	$event->setCancelled(true);
         }
    }

    public function onHunger(PlayerExhaustEvent $event) {
        $event->setCancelled(TRUE);
    }
    
    public function onBreak(BlockBreakEvent $event) {
    	$player = $event->getPlayer();
         if($player->hasPermission("lobby.build")) {
         	$event->setCancelled(false);
         }else{
         	$event->setCancelled(true);
         }
    }
    
    public function onDrop(PlayerDropItemEvent $event){
        $player = $event->getPlayer();
            $event->setCancelled(true);
        }
    
}