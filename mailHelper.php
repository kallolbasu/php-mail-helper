<?php

/**
 * Description of mailHelper
 * this is a simple helper can be used for sending html mails
 * @version 0.1
 * @author kallol
 */
class Mail {

    //put your code here
    public function send($param = NULL) {
        if ($param) {
            $data = $this->process_data($param);
            if ($data['to_email'] != '') {
                if (mail($data['to_email'], $data['mail_subject'], $this->prepare_body($data), $this->prepare_header($data))) {
                    $arrResult = array('status' => 'success');
                } else {
                    $arrResult = array('status' => 'error');
                }
            } else {
                $arrResult = array('status' => 'error');
            }
        } else {
            $arrResult = array('status' => 'error');
        }
        $this->prepare_output($arrResult);
    }

    /**
     * 
     * process  data
     * */
    public function process_data($param) {
        $data = NULL;
        $data['from_name'] = isset($param['from_name']) ? $param['from_name'] : 'Anonymous';
        $data['form_email'] = isset($param['form_email']) ? $param['form_email'] : 'kd151_w@it2dot0.de';
        $data['to_email'] = isset($param['to_email']) ? $param['to_email'] : '';
        $data['mail_subject'] = isset($param['mail_subject']) ? $param['mail_subject'] : '';
        $data['fields'] = array();
        foreach ($param['fields'] as $key => $value) {
            $data['fields'][] = array('text' => ucwords(str_replace('_', ' ', $key)), 'val' => $value);
        }
        return $data;
    }

    /**
     * prepares header of email
     * * */
    public function prepare_header($data) {
        $mailHeader = '';
        $mailHeader .= 'From: ' . $data['from_name'] . ' <' . $data['form_email'] . '>' . "\r\n";
        $mailHeader .= "Reply-To: " . $data['form_email'] . "\r\n";
        $mailHeader .= "MIME-Version: 1.0\r\n";
        $mailHeader .= "Content-Type: text/html; charset=UTF-8\r\n";
        return $mailHeader;
    }

    /**
     * prepares body of email
     * * */
    public function prepare_body($data) {
        $mailBody = "";
        foreach ($data['fields'] as $field) {
            $mailBody .= $field['text'] . ": " . htmlspecialchars($field['val'], ENT_QUOTES) . "<br>\n";
        }
        return $mailBody;
    }

    /**
     * perpare the json output for html
     * 
     * * */
    public function prepare_output($arrResult) {
        session_cache_limiter('nocache');
        header('Expires: ' . gmdate('r', 0));
        header('Content-type: application/json');
        echo json_encode($arrResult);
    }

}
