<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

defined('_JEXEC') or die;

/**
 * Component Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.5
 */
class EventbriteController extends JControllerLegacy
{
    /**
     * @var		string	The default view.
     * @since   1.6
     */
    protected $default_view = 'Eventbrites';

    /**
     * Method to display a view.
     *
     * @param   boolean			If true, the view output will be cached
     * @param   array  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController		This object to support chaining.
     *
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
    {
        $view   = $this->input->get('view', 'eventbrites');
        $layout = $this->input->get('layout', 'eventbrites');
        $id     = $this->input->getInt('id');

        parent::display();

        return $this;
    }
}

