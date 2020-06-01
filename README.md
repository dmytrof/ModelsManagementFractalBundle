DmytrofModelsManagementFractalBundle
====================

This bundle helps you to implement [Fractal by League](https://fractal.thephpleague.com) for 
[DmytrofModelsManagementBundle](https://github.com/dmytrof/ModelsManagementBundle)

## Installation

### Step 1: Install the bundle

    $ composer require dmytrof/models-management-fractal-bundle 
    
### Step 2: Enable the bundle

    <?php
        // config/bundles.php
        
        return [
            // ...
            Dmytrof\ModelsManagementFractalBundle\DmytrofModelsManagementFractalBundle::class => ['all' => true],
        ];
        
        