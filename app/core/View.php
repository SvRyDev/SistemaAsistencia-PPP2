<?php
class View
{
    public static function render($v, $data = [], $layout = 'default')
    {
        extract($data);

        $newView = str_replace('.', '/', $v);
        $viewPath = __DIR__ . '/../views/' . $layout . '/' . $newView . '.php';
        $headerPath = __DIR__ . '/../views/partials/' . $layout . '/header.php';
        $footerPath = __DIR__ . '/../views/partials/' . $layout . '/footer.php';


        if (file_exists($viewPath)) {
            if (isAjax()) {
                require $viewPath;
            } else {
                if (file_exists($headerPath))
                    require $headerPath;
                require $viewPath;
                if (file_exists($footerPath))
                    require $footerPath;
            }
        } else {
            die("Vista '$v' no encontrada.");
        }
    }


    public static function renderWithoutLayout($v, $data = [], $layout = 'default')
    {
        extract($data);
        $newView = str_replace('.', '/', $v);
        $viewPath = __DIR__ . '/../views/' . $layout . '/' . $newView . '.php';

        if (file_exists($viewPath)) {

            require $viewPath;

        } else {
            die("Vista '$v' no encontrada.");
        }
    }


    public static function fetch($v, $data = [])
    {
        extract($data);
        $newView = str_replace('.', '/', $v);
        $viewPath = __DIR__ . '/../views/' . $newView . '.php';

        if (file_exists($viewPath)) {
            ob_start();
            require $viewPath;
            return ob_get_clean();
        } else {
            die("Vista '$v' no encontrada.");
        }
    }

}
