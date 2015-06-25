CHANGELOG
=========

--------------
### 2015-06-25
--------------
###### [cms-sandbox](https://github.com/rzproject/cms-sandbox/commit/8f964f6a2a09e7bc87e11bb79ebaba560f52677e) ######
* fix version of doctrine to avoid Warning: spl_object_hash() expects parameter 1 to be object, null given on OneToMany and ManyToOne relationship introduced on dev-master of doctrine/orm

--------------
### 2015-06-16
--------------
###### [cms-sandbox](https://github.com/rzproject/cms-sandbox/commit/be94a46e1d7587b40f6c830aa11e42f7981edeef#diff-1da2c7edc898c70e5a79a9997c98cecc) ######
* SonataBundle updates reflected on forked version Rmzamora/SonataBundles. Updates are in preparation for Symfony2.7 and Symfony3.0 compatibility. NO BC BREAKS!!!

--------------
### 2015-06-11
--------------
###### [RzNewsBundle](https://github.com/rzproject/NewsBundle/commit/25d5cc25a9e01e087c59d5187f6207d29bf47e18) ######
* added [view count] ()
* documentation for implementation can be found here [https://github.com/rzproject/NewsBundle/blob/1.4/Resources/docs/view_count.md](https://github.com/rzproject/NewsBundle/blob/1.4/Resources/docs/view_count.md)


###### [RzNewsBundle](https://github.com/rzproject/NewsBundle/commit/3ed59b5e422c19b18ab964eb5356abb20b6fb02d) ######
* refactor [postManager](https://github.com/rzproject/NewsBundle/commit/aa5b1e05587ccc578b0506d8189d64c6a586db48)

--------------
### 2015-06-10
--------------
###### [cms-sandbox](https://github.com/rzproject/cms-sandbox/commit/855479c6ac6e5e5f72c7bf10fa5d99d6339788d0) ######
* removed unused bundles

--------------
### 2015-06-05
--------------
###### [RzNewsBundle](https://github.com/rzproject/NewsBundle/commit/d411ca0b32fe65b34f58280daac1a7fb496bfff8) ######
* minor fixes for frontend controller templates
* added admin template overrides

--------------
### 2015-06-09
--------------
###### [RzUserBundle](https://github.com/rzproject/UserBundle/commit/1ff1f0ef391437d34b718ece533258c34606f747) ######
* fix template error

--------------
### 2015-06-02
--------------
###### [Updated AppKernel](https://github.com/rzproject/cms-sandbox/commit/87c7833d6dc2b43c315c9038ae7be42cbe004608) ######


--------------
### 2015-05-27
--------------
###### [Updated Symfony Version to 2.6.9](https://github.com/rzproject/cms-sandbox/commit/71f9fdbaa0c235feee2cbd8fcca4a67a38b39c5e) ######
* This releases fixes a huge performance regression in the development environment. The [#14262](https://github.com/symfony/symfony/pull/14262) was reverted.

--------------
### 2015-05-27
--------------
###### [Updated Symfony Version to 2.6.8](https://github.com/rzproject/cms-sandbox/commit/71f9fdbaa0c235feee2cbd8fcca4a67a38b39c5e) ######
* [CVE-2015-4050](http://symfony.com/blog/cve-2015-4050-esi-unauthorized-access): ESI unauthorized access

###### [RzClassificationBundle](https://github.com/rzproject/ClassificationBundle/commit/c62aef04748be92b49aaace0207e230c8139b17a) ######
* added findOneByContextAndSlug method

--------------
### 2015-05-26
--------------
###### [Updated FOSRest Configuration](https://github.com/rzproject/cms-sandbox/commit/f7de0ad856427cd7cd4254b86aaa6b70e9cd28ca) ######

###### [RzOAuthBundle](https://github.com/rzproject/OAuthBundle/commits/1.2) ######
* Fixed template inheritance

###### [RzUserBundle](https://github.com/rzproject/UserBundle/commit/a6a95549c7461538deafc6244e85b52a91508086) ######
* Fixed template inheritance and breadcrumbs


--------------
### 2015-05-21
--------------
###### [RzOAuthBundle](https://github.com/rzproject/OAuthBundle/commits/1.2) ######
* Configuration fixes [pull-request] from [awesemo](https://github.com/rzproject/OAuthBundle/commits/1.2?author=awesemo)


###### [RzAdminBundle](https://github.com/rzproject/AdminBundle/commit/00367b782142571666a82f9fdc9635bceba4339e) ######
* Fixed admin form label css

###### [RmzamoraBootstrapBundle](https://github.com/rmzamora/BootstrapBundle/commit/d4edc892d765326906beafc0ca06d67d5758bf11) ######
* Updated bootstrap datepicker plugin to version 1.4

--------------
### 2015-05-19
--------------
###### [RzMediaBundle](https://github.com/rzproject/MediaBundle/commit/f8873043a1b3c737eba8c60991681001aac9cf11) ######
* Updated MediaAdmin Lookup Helper template

###### [RmzamoraCacheBundle](https://github.com/rmzamora/SonataCacheBundle/commit/e002157818d4d04fd860020e46794caa0afe375d) ######
* Fixed default values if timeout node is not set

###### [RmzamoraCache](https://github.com/rmzamora/cache/commit/0e305e30c145b7cf0be34e0e0a35368aa997962d) ######
* Increase timeout

###### [RzNewsBundle](https://github.com/rzproject/NewsBundle/commit/3b5d60fb2131ca2289461200b04428e8f2a6cf5e) ######
* removed debug code

--------------
### 2015-05-18
--------------
###### [cms-sanbox](https://github.com/rzproject/cms-sandbox/commits/1.3) ######
* reverted to FOSRestBundle from the forked version

###### [RzClassificationBundle](https://github.com/rzproject/ClassificationBundle/commit/232e818eaf871f4e0d28b39a277226bfa4b0746e) ######
* fixed issue on getting query cache on Category Manager

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