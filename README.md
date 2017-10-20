# BlocksConverter
BlocksConverter is a **PocketMine-MP plugin** that allows you to convert block IDs and datas to another type. For example, if you need to import a Minecraft Java Edition world, you can use it for convert to a Minecraft: BE world!

## Commands
- **/convertqueue <add|remove|status> [levelName|all] (Permission: blocksconverter.commands.convertqueue)**
  - **/cq add <levelname|all>**: It adds one or all worlds/levels in queue before the conversion.
  - **/cq remove <levelname|all>**: It removes one or all worlds/levels from the queue.
  - **/cq status**: It shows the status of current queue.
- **/convert <levelname|queue> [backup=true|false] (Permission: blocksconverter.commands.convert)**
  - **/convert levelname [backup=true|false]**: It directly convert a world/level and it optionally runs a backup (by default is true)
  - **/convert queue [backup=true|false]**: It starts to convert all the worlds/levels in queue.

## Important
This plugin could take some time before the conversion isn't finished. While the conversion, **don't** try to stop or turn off the server until it's finished.
