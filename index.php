<?php

require __DIR__ . '/vendor/autoload.php';

use Hyper\Application\HyperApp;

new HyperApp('Art Gallery', true);

#(new Hyper\Application\Authorization)->register('admin', 's#cr3t00', 'admin');
