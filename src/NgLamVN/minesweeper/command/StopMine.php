<?php

namespace NgLamVN\minesweeper\command;

use pocketmine\command\{CommandSender, Command, PluginCommand};
use pocketmine\{Player, Server};

use NgLamVN\minesweeper\MineSweeper;

class StopMine extends PluginCommand
{
    private $plugin;

    public function __construct(MineSweeper $plugin)
    {
        parent::__construct("stopmine", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("mspe.command.stop");
        $this->setDescription("Start MineSweeper Game !");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("mspe.command.stop")) {
            $sender->sendMessage("U not have perm to use this command");
            return;
        }
        if (!($sender instanceof Player))
        {
            $sender->sendMessage("Please use this command in game");
            return;
        }
        $this->plugin->game->closeGame();
    }
}
