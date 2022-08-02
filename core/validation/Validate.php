<?php
/**
 * Validate
 * 
 * @author Tim Daniëls
 */

namespace core\validation;

class Validate {

    private $_inputName, $_inputNames, $_alias, $_inputValue, $_error;
    public $errors;

    /**
     * Setting html input names and values
     * 
     * @param string $inputName html input name
     * @return mixed object Validate
     */    
    public function input($inputName) {

        if(!empty($inputName)) {

            $this->_inputNames[] = $inputName;
            $doubles = [];

            foreach(array_count_values($this->_inputNames) as $values => $count) {
                if($count > 1) {
                    $doubles[] = $values;
                    
                } 
            }
            if(empty($doubles)) {

                if(post($inputName) !== null) {

                    $this->_inputName = $inputName;
                    $this->_inputValue = post($this->_inputName);

                } else if ($_FILES[$inputName] !== null) {

                    $this->_inputName = $inputName;
                    $this->_inputValue = $_FILES[$this->_inputName];
                } 
            } 
            return $this;
        } 
    }

    /**
     * Setting html input name aliases
     * so aliases can be printed out on view instead of the actual input name
     * 
     * @param string $alias html input value
     * @return mixed object Validate
     */ 
    public function as($alias) {

        if(!empty($alias)) {

            $this->_alias = $alias;
            return $this;
        }
    }

    /**
     * Creating the validation rules and setting error messages
     * 
     * @param string $rules optional
     * @return void
     */     
    public function rules($rules = null) {

        if(!empty($rules) && $rules !== null) {

            foreach($rules as $rule => $value) {

                switch($rule) {
    
                    case 'required':
                        if(empty($this->_inputValue) && $value === true) {

                            $this->message($this->_inputName, "$this->_alias is required.");
                        }
                    break;
                    case 'min':
                        $count_str = strlen($this->_inputValue);
                        if($count_str < $value) {

                            $this->message($this->_inputName, "$this->_alias must be at least $value characters.");
                        }
                    break;
                    case 'max':
                        $count_str = strlen($this->_inputValue);
                        if($count_str > $value) {

                            $this->message($this->_inputName, "$this->_alias can not be more than $value characters.");
                        }
                    break;
                    case 'match':
                        $compare_value = post($value);
                        if($compare_value !== $this->_inputValue) {

                            $this->message($this->_inputName, "$this->_alias does not match.");
                        }
                    break;
                    case 'unique':
                        if(!empty($value)) {

                            $this->message($this->_inputName, "$this->_alias $this->_inputValue already exists.");
                        }
                    break;
                    case 'special':
                        $regex = '/[#$%^&*()+=\\[\]\';,\/{}|":<>?~\\\\]/';
                        if(preg_match($regex, $this->_inputValue)) {

                            $this->message($this->_inputName, "$this->_alias contains special characters.");  
                            $_POST[$this->_inputName] = "";
                        }
                    break;
                    case 'first':
                        if($this->_inputValue[0] !== $value) {

                            $this->message($this->_inputName, "$this->_alias does not start with a $value.");
                        }
                    break;
                    case 'selected':
                        if(empty($_FILES[$this->_inputName]['name']) && $value === true) {

                            $this->message($this->_inputName, "No file is selected.");
                        }
                    break;
                    case 'mimes':
                        if(!in_array($_FILES[$this->_inputName]['type'], $value) ) {

                            $value = implode(', ', $value);
                            $this->message($this->_inputName, "Type of file must be one of the following: $value.");
                        }
                    break;
                    case 'error':
                        if($_FILES[$this->_inputName]['error'] === 1 && $value === true) {

                            $this->message($this->_inputName, "Error found in file.");
                        }
                    break;
                    case 'size':
                        if($_FILES[$this->_inputName]['size'] > $value) {

                            $mbs = $value / 1000000;
                            $mbs = number_format((float)$mbs, 1, '.', '');
                            $filesizeInMbs = $_FILES[$this->_inputName]['size'] / 1000000;
                            $filesizeInMbs = number_format((float)$filesizeInMbs, 1, '.', '');

                            $this->message($this->_inputName, "$filesizeInMbs mb is to big to upload, filesize can't be bigger than $mbs mb.");
                        }
                    break;
                }
            }
        } else {
            $rules = [];
        }
    }

    /**
     * @param string $inputName optional html input name
     * @param string $message optional rule message
     * @return array validation errors
     */     
    private function message($inputName = null, $message = null) {

        $this->errors[] = [$inputName => $message];
        return $this->errors;
    }
}