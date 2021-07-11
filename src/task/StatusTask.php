<?php

namespace NgLamVN\minesweeper\task;

use pocketmine\scheduler\Task;
use NgLamVN\minesweeper\MineSweeper;

class StatusTask extends Task
{
    private MineSweeper $plugin;

    public function __construct(MineSweeper $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(): void
    {
        if (!$this->plugin->game->IsStarted())
        {
            $this->getHandler()->cancel();
        }
        $boms = $this->plugin->game->getBombLeft();
        $players = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game")->getPlayers();
        foreach ($players as $player)
        {
            $player->sendPopup("§bMine(s) left:§e " . $boms);
        }
    }
}
