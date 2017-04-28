<?php

defined('_JEXEC') or die;

class OpinionsModelOpinions extends JModelForm
{

    private function getHash($name, $city, $message)
    {
        return md5($name . $city . $message . '&^%$fghE%440></?]=[+');
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_opinions.opinions', 'opinions', array('load_data' => $loadData));
        if (empty($form)) {
            return false;
        } else {
            return $form;
        }
    }

    public function getScript()
    {
        return 'components/com_opinions/models/forms/opinions.js';
    }

    public function send()
    {
        $sSubject = 'Referencja ' . date('d.m.Y H:i');
        $sFromName = $_POST['inputName'];
        $sCity = $_POST['inputCity'];
        $sPhone = $_POST['inputPhone'];
        $sMailFrom = $_POST['inputEmail'];
        $sMsg = $_POST['inputMessage'];

        $aError = array();

        $oForm = $this->getForm(array(), true);

        $mValidation = $this->validate($oForm, array(
            'inputName' => trim($sFromName),
            'inputCity' => trim($sCity),
            'inputPhone' => trim($sPhone),
            'inputEmail' => trim($sMailFrom),
            'inputMessage' => trim($sMsg)), null);

        if ((bool) $mValidation !== false) {

            // store reference in db
            $stored = $this->storeOpinion($sFromName, $sCity, $sPhone, $sMailFrom, $sMsg);

            if (!$stored) {
                // display error message

                return;
            }

            $oMailer = JFactory::getMailer();
            $oMailer->isHTML(true);
            $oMailer->Encoding = 'base64';
            $oMailer->From = $sMailFrom ? $sMailFrom : 'noreply@sadroveomitkydiamant.cz';
            $oMailer->FromName = $sFromName;
            $oMailer->setSubject($sSubject);
//            $aRecipient = array('piet.kowalski@gmail.com');
            $aRecipient = array(JFactory::getConfig()->get('mailfrom'));
            $oMailer->addRecipient($aRecipient);

            $body = "<h1>DIAMANT</h1><h2>";
            $body .= " {$sSubject}";
            $body .= "</h2><h3>Dodano nową referencję</h3>"
                . "<div>Imię dodającego<br /><b>{$sFromName}</b></div>"
                . "<div>Miasto<br /><b>{$sCity}</b></div>";

            if ($sPhone) {
                $body .= '<div>Telefon dodającego<br /><b>' . $sPhone . '</b></div>';
            }
            if ($sMailFrom) {
                $body .= '<div>Email dodającego<br />' . $sMailFrom . '</div>';
            }

            $body .= "<div>Treść<br /><b>{$sMsg}</b></div>";
            $hash = $this->getHash($sFromName . $sCity . $sMsg);
            $activation_link = JRoute::_(JURI::base() . 'index.php?option=com_opinions&task=activate&hash=' . $hash, true);
            $body .= "<div>Aby powyższa referencja była widoczna na stronie, kliknij w poniższy link:<br /><a href='{$activation_link}'>{$activation_link}</a></div>";
            if (isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['HTTP_USER_AGENT'])) {
                $body .= "<hr /><div>{$_SERVER['REMOTE_ADDR']}<br />{$_SERVER['HTTP_USER_AGENT']}</div>";
            }

            $oMailer->setBody($body);
//	    $oMailer->AddEmbeddedImage(JPATH_COMPONENT . '/assets/logo.png', 'logo_id', 'logo.png', 'base64', 'image/png');
            $mSend = $oMailer->Send();
            if ($mSend !== true) {
                $aError['send'] = $mSend->message;
            } else {
                $aError['send'] = 'ok';
            }
        } else {
            $aError['send'] = JText::_('COM_OPINIONS_INVALID_FORM');
        }

        return $aError;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_opinions.edit.opinions.data', array());
        return $data;
    }

    private function storeOpinion($name, $city, $phone, $email, $opinion)
    {
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // Insert columns.
        $columns = array('name', 'city', 'phone', 'email', 'opinion', 'hash');

        $hash = $this->getHash($name, $city, $opinion);

        // Insert values.
        $values = array(
            $db->quote($name),
            $db->quote($city),
            $db->quote($phone),
            $db->quote($email),
            $db->quote($opinion),
            $db->quote($hash),
        );

        // Prepare the insert query.
        $query
            ->insert($db->quoteName('#__opinions'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));

        // Set the query using our newly populated query object and execute it.
        $db->setQuery($query);
        $db->execute();

        return true;
    }

    public function activateOpinion($hash)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('active') . ' = 1',
            $db->quoteName('activated_at') . ' = ' . $db->quote(date('Y-m-d H:i:s')),
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('hash') . ' = ' . $db->quote($hash)
        );

        $query->update($db->quoteName('#__opinions'))->set($fields)->where($conditions);

        $db->setQuery($query);

        $db->execute();
    }

    public function clearOpinionsCache()
    {
        $dirs = array(
            JPATH_BASE . '/cache/page/',
        );

        $opinions_cache = &JFactory::getCache('mod_profishop_opinions');
        $opinions_cache->clean();

        $articles_cache = &JFactory::getCache('mod_articles_news');
        $articles_cache->clean();

        foreach ($dirs as $dir) {

            if (!is_dir($dir)) {
                continue;
            }

            $scandir = scandir($dir);

            unset($scandir[0]);
            unset($scandir[1]);

            if (empty($scandir)) {
                continue;
            }

            foreach ($scandir as $file) {
                $path = $dir . $file;
                @unlink($path);
            }
        }
    }

    public function getOpinions()
    {
        // Get a db connection.
        $db = JFactory::getDbo();

        // Create a new query object.
        $query = $db->getQuery(true);

        // Select all records from the user profile table where key begins with "custom.".
        // Order it by the ordering field.
        $query->select($db->quoteName(array('name', 'city', 'opinion', 'created_at')));
        $query->from($db->quoteName('#__opinions'));
        $query->where($db->quoteName('active') . ' = 1');
        $query->order('opinion_id DESC');

        // Reset the query using our newly populated query object.
        $db->setQuery($query);

        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();

        return $results;
    }
}
