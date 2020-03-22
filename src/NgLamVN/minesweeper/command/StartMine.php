<?php

namespace NgLamVN\minesweeper\command;

use pocketmine\command\{CommandSender, Command, PluginCommand};
use pocketmine\{Player, plugin\Plugin, Server};

use NgLamVN\minesweeper\MineSweeper;

class StartMine extends PluginCommand
{
    private $plugin;

    public function __construct(MineSweeper $plugin)
    {
        parent::__construct("startmine", $plugin);
        $this->plugin = $plugin;
        $this->setPermission("mspe.command.start");
        $this->setDescription("Start MineSweeper Game !");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender->hasPermission("mspe.command.start"))
        {
            $sender->sendMessage("U not have perm to use this command");
            return;
        }
        if (!($sender instanceof Player))
        {
            $sender->sendMessage("Please use this command in game");
            return;
        }
        if (!isset($args[0]))
        {
            $sender->sendMessage("/startmine <Max X> <Max Y> <Bombs>");
            return;
        }
        if (!isset($args[1]))
        {
            $sender->sendMessage("/startmine <Max X> <Max Y> <Bombs>");
            return;
        }
        if (!isset($args[2]))
        {
            $sender->sendMessage("/startmine <Max X> <Max Y> <Bombs>");
            return;
        }
        if ($args[2] > ($args[0] * $args[1]))
        {
            $sender->sendMessage("Bomb must be lower than " . $args[0] * $args[1]);
            return;
        }
        $this->plugin->game->startGame($args[0], $args[1], $args[2]);
    }
}