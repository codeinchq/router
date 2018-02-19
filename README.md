# Code Inc.'s GUI library

PHP 7 library to manage and render the graphic user interface.

## Usage
```php
<?php
use CodeInc\GUI\PagesManager\PagesManager;
use CodeInc\GUI\Pages\AbstractPage;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;
use CodeInc\GUI\PagesManager\Response\Response;

final class HomePage extends AbstractPage {
	public function process():ResponseInterface {
		$resp = new Response($this);
		$resp->setContent("This is the home page!");
		return $resp;
    }
}

final class SamplePage extends AbstractPage {
	public function process():ResponseInterface {
		$resp = new Response($this);
		$resp->setContent("This is a sample page!");
		return $resp;
    }
}

$pageManager = new PagesManager();
$pageManager->registerPage("/", HomePage::class);
$pageManager->registerPage("/sample.html", SamplePage::class);
$pageManager->processRequest();
```

# License 
This library is published under the MIT license (see the [`LICENSE` file](https://github.com/codeinchq/lib-gui/blob/master/LICENSE)).

