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
- **EVROTEL_BASE_URL**, string, default `http://m01.sipiko.net/` - base url for auto dial requests, statistics files

#### Statistics
You can use [Config](./src/Statistics/Config.php) to configure statistics client.
Also, [Environment Config](./src/Statistics/EnvironmentConfig.php) available:
- **EVROTEL_STATISTICS_BASE_URL**, string, default `https://callme.sipiko.net/` - base url for statistics requests 

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

### Statistics
```php
<?php

use Wearesho\Evrotel;

$baseConfig = new Evrotel\Config($token = 'token', $billCode = 6667);
$config = new Evrotel\Statistics\Config;
/** @var GuzzleHttp\Client $guzzle */

$client = new Evrotel\Statistics\Client($baseConfig, $config, $guzzle);
$client->getCalls($isAuto = true);
```
See [Statistics\Call](./src/Statistics/Call.php) for details.

### Initializer
To initialize call you need to use [Initializer\Client](./src/Initializer/Client.php).
```php
<?php

use Wearesho\Evrotel;

/** @var Evrotel\Config $config */
/** @var GuzzleHttp\Client $guzzle */

$client = new Evrotel\Initializer\Client($config, $guzzle);

$operators = [
   '101',
   '102',  
];
try {
    $client->start('380970000000', $operators);    
}
catch(\RuntimeException $exception) {
    // Evrotel returned `bad` response
}
```

## Contributors
- [Alexander <Horat1us> Letnikow](mailto:reclamme@gmail.com)

## License
[MIT](./LICENSE)
