
VilksFileSearchBundle
==================

Bundle for Symfony2. Allow to find files by content.

Installation
------
Download via composer:

    composer require vilks/file-search-bundle

Add to Kernel:

     new Vilks\FileSearchBundle\VilksFileSearchBundle()

Usage
-------

    php app/console find-file [--engine=ENGINE] [-p, --path=PATH] <needle> 
  

 > **needle**   Searched content
 > **path**       Path for searching. Default current directory.
 > **engine **  Engine for searching. You can write own engine.

----

> by iGrizZli 