<?php

namespace NgLamVN\minesweeper;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use NgLamVN\minesweeper\EventListener;
use NgLamVN\minesweeper\GameManager;

use NgLamVN\minesweeper\command\StartMine;
use NgLamVN\minesweeper\command\StopMine;

class MineSweeper extends PluginBase
{
    public $showid = [];

    public $game;

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->game = new GameManager($this);

        $this->getServer()->getCommandMap()->register("stopmine", new StopMine($this));
        $this->getServer()->getCommandMap()->register("startmine", new StartMine($this));

        $this->getServer()->getLogger()->info("MineSweeperPE TEST VERSION LOADED");
    }

    public function showid (Player $player)
    {
        if (!isset($this->showid[$player->getName()]))
        {
            $this->showid[$player->getName()] = true;
            return;
        }
        if ($this->showid[$player->getName()] == false)
        {
            $this->showid[$player->getName()] = true;
            return;
        }
        else
        {
            $this->showid[$player->getName()] = false;
            return;
        }
    }
    public function Isshowid (Player $player)
    {
        if (!isset($this->showid[$player->getName()]))
        {
            return false;
        }
        else
        {
            return $this->showid[$player->getName()];
        }
    }
}