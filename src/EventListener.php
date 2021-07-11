<?php

namespace NgLamVN\minesweeper;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

class EventListener implements Listener
{
    private MineSweeper $plugin;

    public function __construct(MineSweeper $plugin)
    {
        $this->plugin = $plugin;
    }

	/**
	 * @param BlockBreakEvent $event
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
    public function onBreak (BlockBreakEvent $event)
    {
        if (!$this->plugin->game->IsStarted())
        {
            return;
        }
        $block = $event->getBlock();
        $x = $block->getPos()->getX();
        $y = $block->getPos()->getZ();
        if ($block->getPos()->getY() <> 10)
        {
            return;
        }
        if ($this->plugin->game->IsInMine($x, $y))
        {
            $event->cancel();
        }
    }

	/**
	 * @param PlayerInteractEvent $event
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
    public function onTap (PlayerInteractEvent $event)
    {
        if (!$this->plugin->game->IsStarted())
        {
            return;
        }
        $item = $event->getItem();
        $block = $event->getBlock();
        $x = $block->getPos()->getX();
        $y = $block->getPos()->getZ();
        if ($block->getPos()->getY() <> 10)
        {
            return;
        }
        if (!$this->plugin->game->IsInMine($x, $y))
        {
            return;
        }
        if ($this->plugin->Isshowid($event->getPlayer()))
        {
            $event->getPlayer()->sendMessage("ID: " . $this->plugin->game->core->mine[$x][$y]);
            $this->plugin->showid($event->getPlayer());
            return;
        }
        if ($item->getId() == ItemIds::IRON_SHOVEL)
        {
            $this->plugin->game->explode($x, $y);
        }
        if ($item->getId() == ItemIds::BLAZE_ROD)
        {
            $this->plugin->game->core->setBombFlag($x, $y);
            $this->plugin->game->reloadMine();
            $event->getPlayer()->sendMessage("§f[§bMineSweeper§f]§a Mine flagged!");
        }
        $event->cancel();
    }
}
