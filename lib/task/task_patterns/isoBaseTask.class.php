<?php

/**
 * Adds a custom behavior: handles errors and exceptions
 *
 */
abstract class isoBaseTask extends sfBaseTask
{

    public function checkTaskFatalError()
    {
        $error = error_get_last();
        if ($error['type'] == E_ERROR)
        {
            isoErrorReporting::getInstance()->errorHandler($error['type'], $error['message'], $error['file'], $error['line'], null);
        }
    }

    /**
     * Your should use executeTask method instead!
     *
     * @param array $arguments
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    final protected function execute($arguments = array(), $options = array())
    {
        register_shutdown_function(array($this, "checkTaskFatalError"));
        isoErrorReporting::getInstance()->register();

        try
        {
            return $this->executeTask($arguments, $options);
        }
        catch (Exception $e)
        {
            isoErrorReporting::getInstance()->exceptionHandler($e, "Exception occurred in task " . get_class($this));
            throw $e;
        }

    }

    /**
     * @param array $arguments
     * @param array $options
     * @return mixed
     */
    abstract protected function executeTask($arguments = array(), $options = array());

    protected function initializeContext(array $arguments, array $options)
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
        return isoContext::createInstance($this->configuration);
    }
}