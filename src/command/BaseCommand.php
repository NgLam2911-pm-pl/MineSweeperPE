<?php

namespace NgLamVN\minesweeper\command;

use NgLamVN\minesweeper\MineSweeper;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class BaseCommand extends Command implements PluginOwned
{
	public function __construct(string $name, string $description = "", ?string $usageMessage = null, array $aliases = [])
	{
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
	}

	public function getOwningPlugin() : Plugin
	{
		return MineSweeper::getInstance();
	}
}