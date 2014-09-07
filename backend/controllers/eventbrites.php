<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

class EventbriteControllerEventbrites extends JControllerAdmin
{
    /**
     * Constructor.
     *
     * @param   array  $config	An optional associative array of configuration settings.

     * @return  ContentControllerArticles
     * @see     JController
     * @since   1.6
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }


    /**
     * Proxy for getModel.
     *
     * @param   string	$name	The name of the model.
     * @param   string	$prefix	The prefix for the PHP class name.
     *
     * @return  JModel
     * @since   1.6
     */
    public function getModel($name = 'Eventbrite', $prefix = 'EventbriteModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }
}