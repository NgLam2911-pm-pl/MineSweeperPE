<?php

namespace NgLamVN\minesweeper\command;

use pocketmine\command\CommandSender;

use NgLamVN\minesweeper\MineSweeper;
use pocketmine\player\Player;

class DebugTools extends BaseCommand
{
    private MineSweeper $plugin;

    public function __construct(MineSweeper $plugin)
    {
        parent::__construct("debugmine");
        $this->plugin = $plugin;
        $this->setPermission("mspe.command.debug");
        $this->setDescription("MineSweeper Debug Tools");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player)
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§c Please use this command in-game.");
            return;
        }
        if (!$this->plugin->game->IsStarted())
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§c Please use command while the game is running.");
            return;
        }
        if (!isset($args[0]))
        {
            $sender->sendMessage("MineSweeper DebugTools");
            $sender->sendMessage("showbomb");
            $sender->sendMessage("showbombflag");
            $sender->sendMessage("bombs");
            $sender->sendMessage("blockremain");
            $sender->sendMessage("showblockid");
            return;
        }
        switch ($args[0])
        {
            case "showbomb":
                $this->plugin->game->ShowBombsLose();
                break;
            case "showbombflag":
                $this->plugin->game->ShowBombsWin();
                break;
            case "bombs":
                $sender->sendMessage("Bombs: " . $this->plugin->game->core->bombs);
                break;
            case "blockremain":
                $sender->sendMessage("Block remain: " . $this->plugin->game->getRemainBlock());
                break;
            case "showblockid":
                $this->plugin->showid($sender);
                break;
        }
    }
}
