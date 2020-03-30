<?php

namespace NgLamVN\minesweeper\task;

use pocketmine\scheduler\Task;
use pocketmine\{Player, Server};

use NgLamVN\minesweeper\MineSweeper;

class StatusTask extends Task
{
    private $plugin;

    public function __construct(MineSweeper $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        if (!$this->plugin->game->IsStarted())
        {
            $this->plugin->getScheduler()->cancelTask($this->getTaskId());
        }
        $boms = $this->plugin->game->getBombLeft();
        $players = $this->plugin->getServer()->getLevelByName("Game")->getPlayers();
        foreach ($players as $player)
        {
            $player->sendPopup("§bMine(s) left:§e " . $boms);
        }
    }
}
