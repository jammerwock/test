<?php
spl_autoload_register(
    function ($class) {
        include $class . '.php';
    }
);

set_error_handler(
    create_function(
        '$severity, $message, $filename, $lineno',
        '
           throw new ErrorException($message, 0, $severity, $filename, $lineno);
       '
    )
);

$app = new Application();
try {
    if (defined('STDIN')) {
        $app->start(new CLI());
    } else {
        throw new \RuntimeException("Http protocol is not supported.");
    }

} catch (\RuntimeException $e) {
    echo $e->getMessage() . PHP_EOL;
}
