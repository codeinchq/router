# Code Inc. router library

PHP 7 library to manage routes and send responses to the client.

## Usage
```php
<?php
use CodeInc\Url\Url;
use CodeInc\Router\Pages\AbstractPage;
use CodeInc\Router\Responses\ResponseInterface;
use CodeInc\Router\Responses\HTML\SimpleHtmlResponse;
use CodeInc\Router\Responses\Redirect\RedirectResponse;
use CodeInc\Router\Router;

final class HomePage extends AbstractPage {
	public function process():ResponseInterface {
		$resp = new SimpleHtmlResponse($this);
		$resp->httpHeaders()->addHeader("X-Powered-By", "Code Inc.");
		$resp->setHtmlTitle("Home page");
		$resp->setHtmlBody("<p>The is the home page!</p>");
		return $resp;
    }
}

final class SamplePage extends AbstractPage {
    public function process():ResponseInterface {
    	if ($this->getRequest()->get()->hasVar('redirect')) {
    		return new RedirectResponse($this, new Url("https://www.example.org"));
    	}
    	else {
    	    $resp = new SimpleHtmlResponse($this);
            $resp->setHtmlTitle("Sample page");
            $resp->setHtmlBody("<p>This is a sample page!</p>");
            return $resp;	
    	}
    }
}

$router = new Router();
$router->mapRoute("/", HomePage::class);
$router->mapRoute("/sample.html", SamplePage::class);
$router->processRequest();
```

Mapping:
router -> lookup current route -> calls the corresponding page -> send back a response -> response is processed and sent to the web browser

# License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).

