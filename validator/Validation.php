<?php

include_once __DIR__ . "/../includes/validations.php";


class Validation
{
    public static function validate($data, $keys)
    {
        $validateData = [];
        foreach ($keys as $key => $rules) {
            foreach ($rules as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = isset($ruleParts[1]) ? $ruleParts[1] : null;

                switch ($ruleName) {
                    case 'required':
                        $error = Required::checkRequired($data, $key);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;
                        
                        // case 'required':
                        //     if (!isset($data->$key) || empty($data->$key)) {
                        //         $validateData[$key][] = "$key is a required field!";
                        //     }
                        //     break;

                    case 'minlength':
                        $minLength = (int) $ruleValue;
                        $error = MinLength::checkMinLength($data, $key, $minLength);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;

                        // $minLength = (int) $ruleValue;
                        // if (isset($data->$key) && strlen($data->$key) < $minLength) {
                        //     $validateData[$key][] = "$key should have a minimum length of $minLength characters.";
                        // }

                    case 'maxlength':
                        $maxLength = (int) $ruleValue;
                        $error = MaxLength::checkMaxLength($data, $key, $maxLength);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;

                    case 'email_format':
                        $error = EmailValidation::checkEmail($data, $key);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;

                    case 'numeric':

                        $error = IsNumeric::checkNumeric($data, $key);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;

                    case 'password_format':

                        $error = PasswordFormat::checkPassword($data, $key);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;

                    case 'id_required':

                        $error = IdRequired::checkId($data, $key);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;

                    case 'id_numeric':
                        $error = IsNumeric::checkNumeric($data, $key);
                        if ($error !== null) {
                            $validateData[$key][] = $error;
                        }
                        break;


                    default:
                        break;
                }
            }
        }

        return [
            'validate' => empty($validateData),
            'errors' => $validateData
        ];
    }
}
