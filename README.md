[![ ](https://poggit.pmmp.io/shield.state/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![ ](https://poggit.pmmp.io/shield.api/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![ ](https://poggit.pmmp.io/shield.dl.total/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)

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

## Configuration file
```yaml
---
settings:
  chunk-radius: 10 #It loads all the chunks in the specific radius (starting point is world spawn) and it allows to convert more blocks in the map.

#This is the section for setup the blocks to be converted in all queued worlds
blocks:
  <id>-<metadata>: #The ID and data of block that need to be replaced (usually it should be the MC:PC ID)
    converted-id: <new_id> #The new block ID (usually it should be the MC:BE ID)
    converted-data: <new_metadata> #The new block data
  #Examples
  44-1:
    converted-id: 158
    converted-data: 0
  44-7:
    converted-id: 44
    converted-data: 6
  125-0: #Dropper
    converted-id: 157
    converted-data: 1
  126-0: #Activator Rail
    converted-id: 44
    converted-data: 1
  166-0: #MCPC Barrier
    converted-id: 95
    converted-data: 0
  188-0: #MCPC Repeating command block
    converted-id: 85
    converted-data: 1
  189-0: #MCPC Command block
    converted-id: 85 #Birch fence
    converted-data: 2
  191: #MCPC Dark oak fence
    converted-id: 85
    converted-data: 5
...
```
## Important
This plugin could take some time before the conversion isn't finished. While the conversion, **don't** try to stop or turn off the server until it's finished.
