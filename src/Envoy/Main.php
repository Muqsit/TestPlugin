<?php

namespace Envoy;

use pocketmine\entity\Effect;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\item\Item;
use pocketmine\tile\Sign;
use pocketmine\tile\Tile;

use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener {

  public $data = [
        "status" => "on",
        "level" => [],
        "max_chest_item" => 4,
        "items_chest" => [],
        "max_duration" => 20
    ];

  public function onEnable(){
    $dataResources = $this->getDataFolder() . "/resources/";
      if (!file_exists($this->getDataFolder()))
        @mkdir($this->getDataFolder(), 0755, true);
      if (!file_exists($dataResources))
        @mkdir($dataResources, 0755, true);
    $this->setup = new Config($dataResources . "config.yml", Config::YAML, $this->data);
    $this->setup->save();

  }

  public function onMove(PlayerMoveEvent $event){
    $player = $event->getPlayer;
    $player->getLevel()->setBlock($block, new Block(Block::CHEST), true, true);
    $nbt = new CompoundTag("", [
      new ListTag("Items", []),
      new StringTag("id", Tile::CHEST),
      new IntTag("x", $block->x),
      new IntTag("y", $block->y),
      new IntTag("z", $block->z)
      ]);
    $nbt->Items->setTagType(NBT::TAG_Compound);
    $tile = Tile::createTile("Chest", $block->getLevel()->getChunk($block->x >> 4, $block->z >> 4), $nbt);
      if ($tile instanceof Chest) {
        for ($i = 0; $i <= mt_rand(1, $this->data["max_chest_item"]); $i++)
        $tile->getInventory()->setItem($i, $this->data["items_chest"][mt_rand(0, count($this->data["items_chest"]) - 1)]);
        $player->sendMessage("spam");
        }
    }
}
