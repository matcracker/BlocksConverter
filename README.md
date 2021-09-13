# BlocksConverter
[![ ](https://poggit.pmmp.io/shield.state/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![ ](https://poggit.pmmp.io/shield.api/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![ ](https://poggit.pmmp.io/shield.dl.total/BlocksConverter)](https://poggit.pmmp.io/p/BlocksConverter)
[![Discord](https://img.shields.io/discord/620519017148579841.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/Uf6U78g)

BlocksConverter is a **PocketMine-MP plugin** that allows you to translate blocks from MC: Java Edition to Bedrock or vice-versa.

## Supported world conversion

| From               | To         | Supported |
|--------------------|------------|:---------:|
| Java 1.13+         | PocketMine | No        |
| Java 1.12 or lower | PocketMine | Yes       |

## Important
This plugin could take some time when converting worlds. While the conversion, **don't** try to stop or turn off the server until it's finished.

## Commands

### /convert

Allows to convert a single world or a queue of world to the platform destination (bedrock or java).

**Syntax**: /convert <world_name|queue> [backup=true|false] [platform=bedrock|java] [force=true|false]

**Permission**: blocksconverter.command.convert

**Command parameters**:
- **<world_name|queue>**: it's a mandatory parameter, it requires the name of world to be converted or "queue" to convert a list of worlds. (See **/convertqueue** for more information)
- **[backup=true|false]**: it's an optional parameter, when the value is "true" it creates a backup of your world before the conversion otherwise not. _(Default "true")_
- **[platform=bedrock|java]**: it's an optional parameter, when the value is "bedrock" it converts the world from java to bedrock, when "java" it converts from bedrock to java. _(Default "bedrock")_
- **[force=true|false]**: it's an optional parameter, when the value is "true" you will force the conversion to run **(at your own risk!)** otherwise not. _(Default "false")_

### /convertqueue

Allows managing worlds to be converted. It's useful when you need to convert more than one world.

**Syntax**: /convertqueue <add|remove|status> [world_name|all]

**Permission**: blocksconverter.command.convertqueue

**Command alias**: /cq

**Command parameters**:
- **add**: It adds one or all worlds in queue before the conversion. 
- **remove**: It removes one or all worlds from the queue.
- **status**: It shows the status of current queue.

### /toolblock

Allows showing information about the block you are targeting. (Useful to detect the block ID to replace with).

**Permission**: blocksconverter.command.toolblock