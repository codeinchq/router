# Code Inc.'s GUI library

PHP 7 library to manage and render the graphic user interface.

## Usage
```php
<?php
use CodeInc\GUI\PagesManager\PagesManager;
use CodeInc\GUI\Pages\AbstractPage;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;
use CodeInc\GUI\PagesManager\Response\Response;
use CodeInc\GUI\Templates\HTML\BlankHtml5Template;

final class HomePage extends AbstractPage {
	public function process():ResponseInterface {
		ob_start();
		$tpl = new BlankHtml5Template($this);
		$tpl->setTitle("Home page");
		$tpl->renderHeader();
		?>
		<p>
		    The is the home page !
		</p>
		<?
		$tpl->renderFooter();
		return new Response($this, ob_get_clean());
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
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).

