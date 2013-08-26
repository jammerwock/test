<?php

class CLIView
{
    const RENDER_STRUCTURE = 'renderStructure';
    const VIEW_TABLE = 'viewTable';
    const GENERATION_RESULT = 'generationResult';
    const STR_PAD = 20;

    public function render($data = array(), $callback = null)
    {
        if (is_callable(array('CLIView', $callback))) {
            call_user_func(array('CLIView', $callback), $data);
            return;
        }
        throw new \RuntimeException("Function {$callback} doesn't exist in current scope!");
    }

    private function renderStructure($data)
    {
        $fk = $data['fk'];
        $data = $data['tables'];
        $titles = $this->getTitlesHelper($data);

        foreach ($data as $key => $value) {
            echo 'Table ' . $key . PHP_EOL;
            foreach ($titles as $title) {
                echo str_pad($title, self::STR_PAD);
            }
            echo PHP_EOL;
            foreach ($value as $val) {
                foreach ($val as $type => $param) {
                    echo str_pad($param, self::STR_PAD);
                }
                echo PHP_EOL;
            }
            echo PHP_EOL;
        }
        if (!empty($fk)) {
            echo 'Foreign keys' . PHP_EOL;
            $titles = array_keys($fk[0]);
            foreach ($titles as $title) {
                echo str_pad($title, self::STR_PAD);
            }
            echo PHP_EOL;
            foreach ($fk as $row) {
                foreach ($row as $val) {
                    echo str_pad($val, self::STR_PAD);
                }
                echo PHP_EOL;
            }
            echo PHP_EOL;
        }
    }

    private function viewTable($data = array())
    {
        echo 'View Table Not Implemented' . PHP_EOL;
    }

    private function generationResult($data = array())
    {
        echo 'Generate Data Not Implemented' . PHP_EOL;
    }

    private function getTitlesHelper($data)
    {
        $titles = array();
        foreach ($data as $value) {
            foreach ($value as $val) {
                foreach ($val as $type => $param) {
                    $titles[] = $type;
                }
                return $titles;
            }
        }
    }
}