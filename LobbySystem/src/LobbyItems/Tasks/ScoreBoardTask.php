<?php

namespace LobbyItems\Tasks;

use pocketmine\scheduler\Task;
use LobbyItems\Main;

class ScoreBoardTask extends Task
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $this->plugin->onScore();
     }
}
