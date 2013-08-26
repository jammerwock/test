<?php

class Application
{

    private $protocol;
    private $view;

    public function start(IProtocol $protocol)
    {
        $this->protocol = $protocol;

        try {
            $params = $this->protocol->getParameters();
        } catch (\RuntimeException $e) {
            throw $e;
        }

        if (isset($params['help'])) {
            exit($params['help']);
        }

        $table = new Model(DB::init($params));

        $run = true;
        $text = sprintf(
            'Press: %s v - view data %s s - view structure %s g - generate data %s x - exit%s',
            PHP_EOL,
            PHP_EOL,
            PHP_EOL,
            PHP_EOL,
            PHP_EOL
        );
        $this->view = $protocol->getView();
        $view = $this->view;
        while ($run) {
            echo $text;
            $input = $this->protocol->getInput();
            switch ($input) {
                case 'x':
                case 'X':
                    $run = false;
                    echo 'Bye' . PHP_EOL;
                    break;
                case 'v':
                case 'V':
                    $this->view->render($table->getData(), $view::VIEW_TABLE);
                    break;
                case 's':
                case 'S':
                    echo 'Structure' . PHP_EOL;
                    $this->view->render($table->getTableStructure(), $view::RENDER_STRUCTURE);
                    break;
                case 'g':
                case 'G':
                    $this->view->render($table->generateData(), $view::GENERATION_RESULT);
                    break;
                default:
                    echo "Wrong input $input" . PHP_EOL;
                    break;
            }
        }

    }
}