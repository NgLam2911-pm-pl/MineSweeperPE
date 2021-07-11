<?php

namespace NgLamVN\minesweeper\command;

use pocketmine\command\CommandSender;

use NgLamVN\minesweeper\MineSweeper;
use pocketmine\player\Player;

class StartMine extends BaseCommand
{
    private MineSweeper $plugin;

    public function __construct(MineSweeper $plugin)
    {
        parent::__construct("startmine");
        $this->plugin = $plugin;
        $this->setPermission("mspe.command.start");
        $this->setDescription("Start MineSweeper Game !");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender->hasPermission("mspe.command.start"))
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§c You don't have permission to use this command.");
            return;
        }
        if (!($sender instanceof Player))
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§c Please use this command in game");
            return;
        }
        if (!isset($args[0]))
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§a /startmine§e <Max X> <Max Y> <Mines>");
            $sender->sendMessage("§f[§bMineSweeper§f]§a X & Y:§e Maximum width.");
            $sender->sendMessage("§f[§bMineSweeper§f]§a Mines:§e The maximum amounts of mine that contain in the field.");
            return;
        }
        if (!isset($args[1]))
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§a /startmine <Max X> <Max Y> <Mines>");
            return;
        }
        if (!isset($args[2]))
        {
            $sender->sendMessage("§f[§bMineSweeper§f]§a /startmine <Max X> <Max Y> <Mines>");
            return;
        }
        if ($args[2] > (($args[0] * $args[1]) - 9))
        {
            $bombss = round((($args[0] * $args[1]) * (60 / 100)), 0, PHP_ROUND_HALF_DOWN);
            $sender->sendMessage("§f[§bMineSweeper§f]§c Error! Mine must be lower than:§e " . $bombss . "§c mine(s)");
            return;
        }
        $this->plugin->game->startGame($args[0], $args[1], $args[2]);
    }
}
