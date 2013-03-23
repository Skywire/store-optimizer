<?php

/**
 * The controller which handles incoming requests and performs the necessary 
 * actions for managing A/B tests.
 *
 * @author Tony Holdstock-Brown
 * @since  0.0.1
 */
class THB_ABTest_Admin_ABTestController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Run from all actions which render a view. This loads our layout for 
     * rendering and sets the A/B test menu item as selected.
     *
     * @since 0.0.1
     */
    protected function _init()
    {
        $this->loadLayout()->_setActiveMenu('catalog/abtest');
        return $this;
    }

    /**
     * Loads a grid of A/B tests
     *
     * @since 0.0.1
     */
    public function indexAction()
    {
        $this->_init()
            ->_addContent($this->getLayout()->createBlock('abtest/adminhtml_list_grid_container'))
            ->renderLayout();
    }

    /**
     * Shows the new A/B test form.
     *
     * This loads our tab block on the left and the empty form container as 
     * content.
     *
     * The tab block adds each tab and adds content to our form container.
     *
     * @since 0.0.1
     */
    public function newAction()
    {
        $this->_init()
            ->_addLeft($this->getLayout()->createBlock('abtest/adminhtml_tabs'))
            ->_addContent($this->getLayout()->createBlock('abtest/adminhtml_form'))
            ->renderLayout();
    }

    public function viewAction()
    {
        # Create a new view block and add the children
        $view = $this->getLayout()->createBlock('abtest/adminhtml_view');

        $view->setChild('grid', $this->getLayout()->createBlock('abtest/adminhtml_view_grid'));
        $view->setChild('graph', $this->getLayout()->createBlock('abtest/adminhtml_view_graph'));

        $this->_init()
            ->_addContent($view)
            ->renderLayout();
    }

    /**
     * The form action for creating a new A/B test and its cohorts.
     *
     * @since 0.0.1
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
            try
            {
                $test = Mage::getModel('abtest/test')->addData($data['test']);
                $test->save();

                $variations = 0;
                foreach ($data['cohort'] as $variation)
                {
                    if ($variations >= $data['cohorts']) break;

                    $variations++;
                    $model = Mage::getModel('abtest/variation')->setData($variation);
                    $model->setTestId((int) $test->getId());
                    $model->save();
                }

            } catch (Exception $e) {
                # @TODO: Add exception handling
            }
        }

        $this->_redirect('*/*/edit', array('id' => $test->getId(), '_current' => true));
    }

    /**
     * Called when an admin wants to preview a variation's XML.
     *
     */
    public function previewAction()
    {
        $variation = Mage::getModel('abtest/variation')->load($this->getRequest()->getParam('id'));

        # 1. Load the variation's XML
        # 2. Inject into the user's session under 'abtest_preview', with the 
        #    following format:
        #      array(
        #        'init_at' => date('Y-m-d H:m:s'),
        #        'xml'     => '...',
        #      )
        # 3. Hook into an event that's called on every page request where the 
        #    layout object is initialised
        # 4. Open a new tab with the homepage of the site
        # 5. Show a bar at the top of any frontend request showing that a user
        #    is previewing a variation. Show:
        #      - The variation name
        #      - A button to quit the preview
        #      - The time remaining on the preview
        # 6. Make the preview expire after 5 minutes (definable by options)
    }

}
