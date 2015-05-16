CHANGELOG
=========

--------------
### 2015-05-16
--------------

###### [Updated symfony2 to version 2.6.7](https://raw.githubusercontent.com/symfony/symfony/2.7/CHANGELOG-2.6.md) ######

--------------
### 2015-05-15
--------------

###### [RzSearchBundle](https://github.com/rzproject/SearchBundle/commit/678bf8301f54f0d3bd877269df34035d4618851f) ######
* updated solr related classes [STABLE] for SOLR 5.1
* change source to forked version of solarium to fix curl error when using SOLR 5.1

--------------
### 2015-05-14
--------------

###### [RzSearchBundle](https://github.com/rzproject/SearchBundle/commit/44e76c00aa112ecceb42c6b2979d8739445da7ec) ######
* added block settings to allow override of BlockService class and added configuration for block template

###### [RzNewsBundle](https://github.com/rzproject/NewsBundle/commit/617818a463b15a153771780fcd579ef9d2dec0cf) ######
* removed duplicate validation

###### [RzNewsBundle](https://github.com/rzproject/NewsBundle/commit/617818a463b15a153771780fcd579ef9d2dec0cf) ######
* removed duplicate validation

###### [RzBlockBundle](https://github.com/rzproject/BlockBundle/commit/d1523b52db15e7fa4c6c22cba9305fc6f7e67731) ######
* fixed use statement for inherited class on php 5.4
 
###### [RzPageBundle](https://github.com/rzproject/PageBundle/commit/a50359b35267bc2a09f3f1e7509a3abdfd33f8b3) ######
* fixed use statement for inherited class on php 5.4
* added default template for search result

###### [CCDNUserSecurityBundle](https://github.com/rmzamora/CCDNUserSecurityBundle/commit/6aa1b33e23521ef76ac96b5bdaf6ca3abf81efb5) ######
* added new 2.1 branch to prevent [BC-BREAK] 
* dev-master is now the development branch

###### [RzUserSecurityBundle](https://github.com/rzproject/UserSecurityBundle/commits/1.1) ######
* added new 1.1 branch to prevent [BC-BREAK] 
* dev-master is now the development branch

--------------
### 2015-05-13
--------------

###### [RzSearchBundle](https://github.com/rzproject/SearchBundle/commit/1ca27d429818ce65dd11990305aca5bde1cfb427) ######
* added array checks for LuceneIndexer and Config Manager
* fixed error when indexing News Comments for Persistent Collection instance

--------------
### 2015-05-12
--------------

###### [RmzamoraJqueryBundle](https://github.com/rmzamora/JqueryBundle/commits/1.11.3) ######
* updated jquery to 1.11.3 and jquery-ui to 1.11.4

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