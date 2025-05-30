<?php
class View
{
    public static function render($view, $data = [], $layout = 'default')
{
    extract($data);
    
    $newView = str_replace('.', '/', $view);
    $viewPath = __DIR__ . '/../views/' . $layout . '/' . $newView .'.php';
    $headerPath = __DIR__ . '/../views/partials/' . $layout . '/header.php';
    $footerPath = __DIR__ . '/../views/partials/' . $layout . '/footer.php';

    
    if (file_exists($viewPath)) {
        if (isAjax()) {
            require $viewPath;
        } else {
            if (file_exists($headerPath)) require $headerPath;
            require $viewPath;
            if (file_exists($footerPath)) require $footerPath;
        }
    } else {
        die("Vista '$view' no encontrada.");
    }
}

}
