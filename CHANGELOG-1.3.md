CHANGELOG
=========

--------------
### 2015-05-04
--------------

###### [cms-sanbox](https://github.com/rzproject/cms-sandbox/commits/1.3) ######
* updated lockfile latest stable build.
* updated config files in relation to [RzMediaBundle](https://github.com/rzproject/MediaBundle/commit/424abd771f77efbe32f422bf43ad2c5da78c2876) changes.
* added formatter and media config files
* removed bxslider and featured galleries blocks from config (sonnata_block.yml)

###### [RzMediaBundle](https://github.com/rzproject/MediaBundle/commit/424abd771f77efbe32f422bf43ad2c5da78c2876) ######
* cleanup block services
* added configuration for block services classes and templates
* removed BxSlider block service
* removed FeaturedGalleriesBlockService

--------------
### 2015-05-01
--------------

###### RzFormatterBundle ######
* Added config file for RzFormatterBundle to configure block classes and templates
* added configureClassesToCompile

###### RzAdminBundle ######
* added configureClassesToCompile

--------------
### 2015-04-30
--------------

###### cms-sanbox ######
* Added config file for RzBlockBundle to configure block classes and templates

###### RzBlockBundle ######
* fix TextBlockService admin form

###### RzPageBundle ######
* fix SharedBlockService frontend template rendering error

###### RzMediaBundle ######
* added placeholder for missing thumbnail on admin
* added twig file_exist extension