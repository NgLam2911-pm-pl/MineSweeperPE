<?php

namespace NgLamVN\minesweeper;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use NgLamVN\minesweeper\command\StartMine;
use NgLamVN\minesweeper\command\StopMine;
use NgLamVN\minesweeper\command\DebugTools;
use pocketmine\utils\SingletonTrait;

class MineSweeper extends PluginBase
{
	use SingletonTrait;

    public array $showid = [];

    public GameManager $game;

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->game = new GameManager($this);

        $this->getServer()->getCommandMap()->register("stopmine", new StopMine($this));
        $this->getServer()->getCommandMap()->register("startmine", new StartMine($this));
        $this->getServer()->getCommandMap()->register("debugmine", new DebugTools($this));

        $this->getServer()->getLogger()->info("MineSweeperPE TEST VERSION LOADED");
    }

    public function showid (Player $player): void
    {
        if (!isset($this->showid[$player->getName()]))
        {
            $this->showid[$player->getName()] = true;
            return;
        }
        if ($this->showid[$player->getName()] == false)
        {
            $this->showid[$player->getName()] = true;
        }
        else
        {
            $this->showid[$player->getName()] = false;
        }
    }
    public function Isshowid (Player $player): bool
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