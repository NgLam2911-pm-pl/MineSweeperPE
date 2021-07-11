<?php

namespace NgLamVN\minesweeper\command;

use pocketmine\command\CommandSender;
use NgLamVN\minesweeper\MineSweeper;
use pocketmine\player\Player;

class StopMine extends BaseCommand
{
    private MineSweeper $plugin;

    public function __construct(MineSweeper $plugin)
    {
        parent::__construct("stopmine");
        $this->plugin = $plugin;
        $this->setPermission("mspe.command.stop");
        $this->setDescription("Start MineSweeper Game !");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender->hasPermission("mspe.command.stop")) {
            $sender->sendMessage("§f[§bMineSweeper§f]§c You don't have permission to use this command.");
            return;
        }
        if (!($sender instanceof Player))
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§c Please use this command in game");
            return;
        }
        $this->plugin->game->closeGame();
    }
}
