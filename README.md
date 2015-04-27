cms-sandbox
===========

rzproject cms-sandbox - CMS sandbox based on SonataProject and RzBundles

Versions
--------

* 2.0-dev - Refactored admin bundle using Bootstrap3

* 1.3.x-dev - Development version with updated page, classification and news bundle

* 1.3.0.x-dev - Development version before page, classification and news bundle refactor

* 1.2.x-dev - Latest stable version based on Sonata 2.3 

* 1.2.0-dev - Deprecated stable version based on Sonata 2.3 

* 1.1.x-dev - Removed front-editing in favor of page composer from backend

* 1.0.x-dev - [BC Break] applies context on classification affects media, news and classification

* 1.0.0 First Stable Version


What's inside?
--------------

cms-sandbox Standard Edition is based on rz-platform and comes pre-configured with the following bundles:

Symfony Standard Edition
------------------------

* FrameworkBundle
* SensioFrameworkExtraBundle
* DoctrineBundle
* TwigBundle
* SwiftmailerBundle
* MonologBundle
* AsseticBundle
* JMSSecurityExtraBundle
* WebProfilerBundle (in dev/test env)
* SensioDistributionBundle (in dev/test env)
* SensioGeneratorBundle (in dev/test env)
* AcmeDemoBundle (in dev/test env)

Uses Forked version of Sonata Bundles
-------------------------------------

* SonataAdminBundle - The missing Symfony2 Admin Generator
* SonataMediaBundle
* SonataPageBundle
* SonataUserBundle
* SonataEasyExtendsBundle
* SonataIntlBundle
* SonataNewsBundle
* SonatajQueryBundle



FOS Bundles
-----------

* FOSUserBundle


Behat Bundles
-------------

* MinkBundle
* BehatBundle


Rz Bundles
----------

* RzDoctrineORMAdminBundle
* RzAdminBundle
* RzUserBundle
* RzBlockBundle
* RzMediaBundle
* RzPageBundle
* RzNewsBundle


Installation from Sonata Sandbox
--------------------------------

Run the following commands::

    git clone https://github.com/rzproject/cms-sandbox cms-sandbox
    cd cms-sandbox
    rm -rf .git
    git init
    git add .gitignore *
    git commit -m "Initial commit (from the Sonata Sandbox)"
    php bin/vendors install
    git add *
    git commit -m "add submodules"
    cp app/config/parameters.yml.sample app/config/parameters.yml
    cp app/config/parameters.yml.sample app/config/validation_parameters.yml
    cp app/config/parameters.yml.sample app/config/production_parameters.yml

.. note::

  The ``bin/vendor`` script does not behave like the one provided by the Symfony2 Standard Edition.
  The script install vendors as git submodules.


Database initialization
-----------------------

At this point, the ``app/console`` command should start with no issues. However some you need the complete some others step:

* database configuration (edit the app/config/parameters.yml file)

then runs the commands::

    app/console doctrine:database:create
    app/console doctrine:schema:create

Fixtures (For Demo Purpose Only)
--------------------------------

To have some actual data in your DB, you should load the fixtures by running::

    app/console doctrine:fixtures:load


Assets Installation
-------------------

Your frontend still looking weird because bundle assets are not installed. Run the following command to install assets for all active bundles under public directory::

    app/console assets:install web --symlink
    app/console assetic:dump



Sonata Page Bundle
------------------

By default the Sonata Page bundle is activated, so you need to starts 2 commands before going further::

    app/console sonata:page:create-site --enabled=true --name=localhost --host=localhost --relativePath=/ --enabledFrom=now --enabledTo="+10 years" --default=true
    app/console sonata:page:update-core-routes --site=all
    app/console sonata:page:create-snapshots --site=all

.. note::

    The ``update-core-routes`` populates the database with ``page`` from the routing information.
    The ``create-snapshots`` create a snapshot (a public page version) from the created pages.


Create User
-----------

.. note::

    Only execute if you not loading fixtures

Create User::

    php app/console fos:user:create admin youremail@example.com admin

Add Role::

    php app/console fos:user:promote admin ROLE_SUPER_ADMIN


Unit Testing
------------

Automatic Unit Testing with ``watchr``::

    gem install watchr
    cd /path/to/symfony-project
    watchr phpunit.watchr



Enjoy!

A Fork from Sonata Sandbox https://github.com/sonata-project/sandbox
