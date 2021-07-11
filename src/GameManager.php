<?php

declare(strict_types = 1);

namespace NgLamVN\minesweeper;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;

use NgLamVN\minesweeper\task\StatusTask;

class GameManager
{
    private MineSweeper $plugin;

    public Core $core;

    public bool $start;

    public function getPlugin(): MineSweeper
    {
        return $this->plugin;
    }

    public function __construct(MineSweeper $plugin)
    {
        $this->plugin = $plugin;
        $this->start = false;
    }

    public function startGame($x, $y, $bombs): void
    {
        $this->closeGame();
        $this->core = new Core($x, $y, $bombs);
        $this->start = true;
        $this->reloadMine();
        $task = new StatusTask($this->plugin);
        $this->plugin->getScheduler()->scheduleRepeatingTask($task, 40);
        $this->giveItem();
    }
    public function closeGame(): void
    {
        $world = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game");
        if (!isset($this->core->maxx))
        {
            return;
        }
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                $world->setBlock($pos, VanillaBlocks::AIR(), false);
            }
        }
        $this->start = false;
    }

    public function reloadMine(): void
    {
        $level = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                switch ($this->core->mine[$i][$j])
                {
                    case -1:
                    case -2:
                        $level->setBlock($pos, VanillaBlocks::AIR(), false);
                        break;
                    case 0:
					case 9:
                        $level->setBlock($pos, VanillaBlocks::QUARTZ(), false);
                        break;
                    case 1:
                        $level->setBlock($pos, VanillaBlocks::STONE(), false);
                        break;
                    case 2:
                        $level->setBlock($pos, VanillaBlocks::GRASS(), false);
                        break;
                    case 3:
                        $level->setBlock($pos, VanillaBlocks::DIRT(), false);
                        break;
                    case 4:
                        $level->setBlock($pos, VanillaBlocks::COBBLESTONE(), false);
                        break;
                    case 5:
                        $level->setBlock($pos, VanillaBlocks::OAK_PLANKS(), false);
                        break;
                    case 6:
                        $level->setBlock($pos, VanillaBlocks::WOOL(), false);
                        break;
                    case 7:
                        $level->setBlock($pos, VanillaBlocks::GLASS(), false);
                        break;
                    case 8:
                        $level->setBlock($pos, VanillaBlocks::OBSIDIAN(), false);
                        break;
                    case 10:
					case 11:
                        $level->setBlock($pos, VanillaBlocks::GOLD(), false);
                        break;
                }
            }
        }
    }

    public function ShowBombsLose($tapx = 0, $tapy = 0): void
    {
        $level = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] == 9)
                {
                    $level->setBlock($pos, VanillaBlocks::TNT(), false);
                }
                if ($this->core->mine[$i][$j] == 11)
                {
                    $level->setBlock($pos, VanillaBlocks::REDSTONE(), false);
                }
            }
        }
        if (($tapx <> 0) and ($tapy <> 0))
        {
            $pos2 = new Vector3($tapx, 10, $tapy);
            $level->setBlock($pos2, VanillaBlocks::BEDROCK(), false);
        }
    }

    public function ShowBombsWin(): void
    {
        $level = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game");
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++) {
            for ($j = 1; $j <= $y; $j++) {
                $pos = new Vector3($i, 10, $j);
                if ($this->core->mine[$i][$j] == 9) {
                    $level->setBlock($pos, VanillaBlocks::GOLD(), false);
                }
            }
        }
    }

    public function explode($x, $y): void
    {
        $this->core->explode($x, $y);
        $this->reloadMine();
        if ($this->core->IsGameOver())
        {
            $this->ShowBombsLose($x, $y);
            $this->plugin->getServer()->getLogger()->info("§bShow lose:§a " . $x . " " . $y);
            $this->plugin->getServer()->broadcastMessage("§f[§bMineSweeper§f]§c GAME OVER, use§e /startmine§c to start another MineSweeper game.");
            $this->start = false;
            $this->clearItem();
            return;
        }
        $blocks = $this->getRemainBlock();
        $this->plugin->getServer()->getLogger()->info("§aBlocks Remain:§e " .$blocks);
        $bombs = $this->core->bombs;
        $this->plugin->getServer()->getLogger()->info("§aMine:§e " .$bombs);
        if ($blocks == 0)
        {
            $this->ShowBombsWin();
            $this->plugin->getServer()->broadcastMessage("§f[§bMineSweeper§f]§a Congratstulation! You've won the game, use§e /startmine§a to start another MineSweeper game.");
            $this->start = false;
        }
    }

    public function getRemainBlock(): int
    {
        $blocks = 0;
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                if (($this->core->mine[$i][$j] == 0) or ($this->core->mine[$i][$j] == 11))
                {
                    $blocks++;
                }
            }
        }
        return $blocks;
    }

    public function getBombLeft(): int
    {
        $dbomb = 0;
        $rbomb = $this->core->bombs;
        $x = $this->core->maxx;
        $y = $this->core->maxy;
        for ($i = 1; $i <= $x; $i++)
        {
            for ($j = 1; $j <= $y; $j++)
            {
                if (($this->core->mine[$i][$j] == 10) or ($this->core->mine[$i][$j] == 11))
                {
                    $dbomb++;
                }
            }
        }
        return ($rbomb - $dbomb);
    }

    public function IsStarted(): bool
    {
        return $this->start;
    }

    public function IsInMine($x, $y): bool
    {
        return $this->core->IsExplodeable($x, $y);
    }

    public function giveItem()
    {
        $players = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game")->getPlayers();
        foreach ($players as $player)
        {
            $player->getInventory()->clearAll();
            $item1 = VanillaItems::IRON_SHOVEL();
            $item2 = VanillaItems::BLAZE_ROD();
            $item1->setCustomName("§c§lExplode§r\n§eTap to Explode");
            $item2->setCustomName("§e§lFlag§r\n§eTap to set flag");
            $player->getInventory()->addItem($item1);
            $player->getInventory()->addItem($item2);
        }
    }
    public function clearItem()
    {
        $players = $this->plugin->getServer()->getWorldManager()->getWorldByName("Game")->getPlayers();
        foreach ($players as $player)
        {
            $player->getInventory()->clearAll();
        }
    }
}
