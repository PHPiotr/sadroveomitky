<?php

defined('_JEXEC') or die;

class OpinionsController extends JControllerForm
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function display($cachable = false, $urlparams = false)
    {
        return parent::display($cachable, $urlparams);
    }

    public function send()
    {

        if (!isset(
                $_POST['inputSubject'], $_POST['inputName'], $_POST['inputCity'], $_POST['inputPhone'], $_POST['inputEmail'], $_POST['inputMessage'])
        ) {
            $this->setRedirect(JRoute::_('index.php?option=com_opinions&view=opinions', false), JText::_('COM_OPINIONS_WRONG_PARAMS'));
        }

        JSession::checkToken() or die('Invalid token');

        $oEmailModel = $this->getModel();
        $mSession = &JFactory::getSession();
        $mSession->set('inputName', trim($_POST['inputName']));
        $mSession->set('inputCity', trim($_POST['inputCity']));
        $mSession->set('inputPhone', trim($_POST['inputPhone']));
        $mSession->set('inputEmail', trim($_POST['inputEmail']));
        $mSession->set('inputMessage', trim($_POST['inputMessage']));

        $aError = array();

        if (!empty($_POST['inputSubject']) || trim($_POST['inputSubject']) !== '') {
            $aError['inputSubject'] = JText::_('COM_OPINIONS_ROBOT_ERROR');
        }

        $_POST['inputName'] = trim($_POST['inputName']);
        if (empty($_POST['inputName']) || trim($_POST['inputName']) === '') {

            $aError['inputName'] = JText::_('COM_OPINIONS_ENTER_NAME');
        }

        $_POST['inputCity'] = trim($_POST['inputCity']);
        if (empty($_POST['inputCity']) || trim($_POST['inputCity']) === '') {

            $aError['inputCity'] = JText::_('COM_OPINIONS_ENTER_CITY');
        }

        if (empty($_POST['inputEmail']) || trim($_POST['inputEmail']) === '') {
            // $aError['inputEmail'] = JText::_('COM_OPINIONS_ENTER_EMAIL');
        } else if (!filter_var($_POST['inputEmail'], FILTER_VALIDATE_EMAIL)) {
            $aError['wrongEmail'] = true;
        }

        if (empty($_POST['inputMessage']) || trim($_POST['inputMessage']) === '') {
            $aError['inputMessage'] = JText::_('COM_OPINIONS_ENTER_MESSAGE');
        }

        if (empty($aError)) {

            $mSend = $oEmailModel->send();

            $aError = $mSend;

            if ($mSend['send'] === 'ok') {
                $mSession->clear('subject');
                $mSession->clear('inputName');
                $mSession->clear('inputCity');
                $mSession->clear('inputPhone');
                $mSession->clear('inputEmail');
                $mSession->clear('inputMessage');
                $sMsg = JText::_('COM_OPINIONS_SUCCESS');
                $aError = array('send' => $sMsg, 'success' => true);
            }
        } else {
            if (isset($aError['inputSubject'])) {
                $sMsg = JText::_('COM_OPINIONS_ROBOT_ERROR');
            } else {
                if (isset($aError['wrongEmail'])) {
                    $sMsg = JText::_('COM_OPINIONS_INVALID_EMAIL');
                } else {
                    $sMsg = JText::_('COM_OPINIONS_FAILURE');
                }
            }

            $aError['notsend'] = $sMsg;
        }

        if (isset($_POST['ajax'])) {
            echo json_encode($aError);
        } else {
            $this->setRedirect(JRoute::_('index.php?option=com_opinions&view=opinions', false), implode('<br />', $aError));
        }
    }

    public function activate()
    {
        if (!isset($_GET['hash'])) {
            throw new Exception(null, 404);
        }

        $hash = (string) $_GET['hash'];

        $model = $this->getModel();

        $model->activateOpinion($hash);
        $model->clearOpinionsCache();

        $this->setRedirect(JURI::root());
    }
}
