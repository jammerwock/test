<?php

class CLI implements IProtocol
{

    private $params;
    private $dbparams;

    public function __construct()
    {
        $this->params = array(
            '' => 'help',
            'h:' => 'host:',
            'u:' => 'user:',
            'p:' => 'password:',
            'd:' => 'database:',
        );
        $this->dbparams = array('host' => '127.0.0.1', 'user' => null, 'password' => null, 'database' => null);
    }

    public function getParameters()
    {
        $errors = array();
        $options = getopt(implode('', array_keys($this->params)), $this->params);

        if (isset($options['host']) || isset($options['h'])) {
            $this->dbparams['host'] = strval(isset($options['host']) ? $options['host'] : $options['h']);
        }

        if (isset($options['user']) || isset($options['u'])) {
            $this->dbparams['user'] = strval(isset($options['user']) ? $options['user'] : $options['u']);
        } else {
            $errors[] = 'User required';
        }

        if (isset($options['password']) || isset($options['p'])) {
            $this->dbparams['password'] = strval(isset($options['password']) ? $options['password'] : $options['p']);
        }

        if (isset($options['database']) || isset($options['d'])) {
            $this->dbparams['database'] = strval(isset($options['database']) ? $options['database'] : $options['d']);
        } else {
            $errors[] = 'Database required';
        }

        if (isset($options['help']) || count($errors)) {
            $help = "
                    Usage:  php run.php [--help] [-h|--host] [-u|--user] [-p|--password] [-d|--database]

                    Options:
                                --help      Show this message
                            -h  --host      Server hostname (default: localhost)
                            -u  --user      User
                            -p  --password  Password (default: no password)
                            -d  --database  Database
                    Example:
                            php run.php --user=root --password=12345 --database=test
                    " . PHP_EOL;
            if ($errors) {
                $help .= 'Errors:' . PHP_EOL . implode("\n", $errors) . PHP_EOL;
                throw new \RuntimeException($help);
            }
            $this->dbparams = array('help' => $help);
        }
        return $this->dbparams;
    }

    public function getView()
    {
        return new CLIView();
    }

    public function getInput()
    {
        while (false !== ($string = fgets(STDIN))) {
            return trim($string);
        }
    }
}