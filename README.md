# Evrotel API Integration
[![Build Status](https://travis-ci.org/wearesho-team/evrotel.svg?branch=master)](https://travis-ci.org/wearesho-team/evrotel)
[![codecov](https://codecov.io/gh/wearesho-team/evrotel/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/evrotel)

This library created using
[API Docs](https://docs.google.com/document/d/10wGFGzql3ix8dH_JfXsp2BHyjbbFnEdZr089QRStAXY/edit#heading=h.la6q3gdlwnb9)
## Installation

```bash
composer require wearesho-team/evrotel
```

## Usage

### Configuration
You can use [Config](./src/Config.php) to configure app.
Also, [Environment Config](./src/EnvironmentConfig.php) available:
- **EVROTEL_TOKEN** - token received from Evrotel manager

## Receiver

```php
<?php

use Wearesho\Evrotel;

/** @var Evrotel\ConfigInterface $config */

$receiver = new Evrotel\Receiver($config);

try {
    $request = $receiver->getRequest();
    
    if($request instanceof Evrotel\Receiver\Request\Start) {
        /**
          * You have to return ID in response body
          * to receive it in call end request  
          */
        return 1;
    } elseif ($request instanceof Evrotel\Receiver\Request\End) {
        // Do something with call end request
        
        return;
    }
}
catch (Evrotel\Exceptions\AccessDenied $denied) {
    // Return 403
}
catch(Evrotel\Exceptions\BadRequest $badRequest) {
    // Return 400
}
```

TODO: Write usage docs

## Contributors
- [Alexander <Horat1us> Letnikow](mailto:reclamme@gmail.com)

## License
[MIT](./LICENSE)
