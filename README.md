[![ ](https://poggit.pmmp.io/shield.state/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![ ](https://poggit.pmmp.io/shield.api/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![ ](https://poggit.pmmp.io/shield.dl.total/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)

# BlocksConverter
BlocksConverter is a **PocketMine-MP plugin** that allows you to translate blocks from MC: Java Edition to MCPE.

## Commands
- **/convertqueue <add|remove|status> [world_name|all] (Permission: blocksconverter.command.convertqueue)**
  - **/cq add <world_name|all>**: It adds one or all worlds in queue before the conversion.
  - **/cq remove <world_name|all>**: It removes one or all worlds from the queue.
  - **/cq status**: It shows the status of current queue.
- **/convert <world_name|queue> [backup=true|false] (Permission: blocksconverter.command.convert)**
  - **/convert [world_name] [backup=true|false]**: It directly convert a world (without quotes!) and it optionally runs a backup (by default is true)
  - **/convert queue [backup=true|false]**: It starts to convert all the worlds in queue.
- **/toolblock (Permission: blocksconverter.command.toolblock)**
  - Allows to show information about the block you are targetting. (Useful to detect the block ID to replace with).
  
## Important
This plugin could take some time before the conversion isn't finished. While the conversion, **don't** try to stop or turn off the server until it's finished.
