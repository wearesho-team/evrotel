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
- **EVROTEL_TOKEN**, string - token received from Evrotel manager
- **EVROTEL_BILL_CODE**, integer - bill code received from Evrotel manager
- **EVROTEL_BASE_URL**, string, default `http://m01.sipiko.net/` - base url for auto dial requests

### Receiver

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

### Auto Dial
Before call initiating you need to push media file

```php
<?php

use Wearesho\Evrotel;

/** @var Evrotel\ConfigInterface $config */
/** @var GuzzleHttp\Client $client */

$repository = new Evrotel\AutoDial\MediaRepository($config, $client);
$link = 'https://server.com/file.wav'; // Public link to auto dial file, Mono, 16 Bits,8000Hz, wav

try {
    $fileName = $repository->push($link);
}
catch(Evrotel\Exceptions\AutoDial\PushMedia $media) {
    // handle errors
}
```

After pushing media you can use your file to make dials:
```php
<?php

use Wearesho\Evrotel;

/** @var Evrotel\ConfigInterface $config */
/** @var GuzzleHttp\Client $client */

$worker = new Evrotel\AutoDial\Worker($config, $client);

/** @var string $fileName returned from MediaRepository */
/** @var string $phone in format 380XXXXXXXXX */

$request = new Evrotel\AutoDial\Request($fileName, $phone);

$worker->push($request);
```

## Contributors
- [Alexander <Horat1us> Letnikow](mailto:reclamme@gmail.com)

## License
[MIT](./LICENSE)
