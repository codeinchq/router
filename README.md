# Code Inc.'s GUI library

PHP 7 library to manage and render the graphic user interface.

## Usage
```php
<?php
use CodeInc\GUI\PagesManager\PagesManager;
use CodeInc\GUI\Pages\AbstractPage;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;
use CodeInc\GUI\PagesManager\Response\Response;
use CodeInc\GUI\PagesManager\Response\Html5Response;

final class HomePage extends AbstractPage {
	public function process():ResponseInterface {
		$resp = new Html5Response($this);
		$resp->setPageTitle("Home page");
		$resp->setHtmlBody("<p>The is the home page!</p>");
		return $resp;
    }
}

final class SamplePage extends AbstractPage {
    public function process():ResponseInterface {
        $resp = new Html5Response($this);
        $resp->setPageTitle("Sample page");
        $resp->setHtmlBody("<p>This is a sample page!</p>");
        return $resp;
    }
}

$pageManager = new PagesManager();
$pageManager->registerPage("/", HomePage::class);
$pageManager->registerPage("/sample.html", SamplePage::class);
$pageManager->processRequest();
```

# License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).

