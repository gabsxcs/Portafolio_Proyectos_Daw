<?php

class Controller {
    
    public function sanitize($var, $type = "string") {
        $flags = NULL;
        $var = htmlspecialchars(stripslashes(trim($var)));
        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                $output = filter_var($var, $filter);
                break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                $output = filter_var($var, $filter);
                break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
                $flags = FILTER_FLAG_EMAIL_UNICODE;
                $output = filter_var($var, $filter, $flags);
                break;
            case 'string':
            default:
                //$filter = FILTER_SANITIZE_STRING; Deprecated
                $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
                $output = filter_var($var, $filter, $flags);
                break;
        }
        return ($output);
    }
    
    public function validateItem($var, $type) {
        $regexes = Array(
            'date' => "^[0-9]{1,2}[-/][0-9]{1,2}[-/][0-9]{4}\$",
            'amount' => "^[-]?[0-9]+\$",
            'number' => "^[-]?[0-9,]+\$",
            'nom' => "^[a-zA-ZñÑàáèéíòóúÀÁÈÉÍÒÓÚçÇ' .-]*$",
            'alfanum' => "^[0-9a-zA-ZñÑàáèéíòóúÀÁÈÉÍÒÓÚçÇ' ,.-_\\s\?\!]*$",
            'not_empty' => "[a-z0-9A-Z]+",
            'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
            'phone' => "^[0-9]{9,11}\$",
            'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
            'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
            'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
            '2digitopt' => "^\d+(\,\d{2})?\$",
            '2digitforce' => "^\d+\,\d\d\$",
            'anything' => "^[\d\D]{1,}\$"
        );
        if (array_key_exists($type, $regexes)) {
            $returnval = filter_var($var, FILTER_VALIDATE_REGEXP, array(
                "options" => array(
                    "regexp" => '!' . $regexes[$type] . '!i'
                )
            )) !== false;
            return ($returnval);
        }
        $filter = false;
        switch ($type) {
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;
                break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
                break;
            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
                break;
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
                break;
        }
        return ($filter === false) ? false : (filter_var($var, $filter) !== false ? true : false);
    }
}

