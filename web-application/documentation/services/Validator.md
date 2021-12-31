# Validator

        /*
         * Possible validator rules for the setter
         */

        /*
         * ValueTypes << REQUIRED
         *  available valueTypes
         *      ValueType::String
         *	    ValueType::Url
         *	    ValueType::NumberInt
         *	    ValueType::Integer
         *	    ValueType::Email
         *	    ValueType::Boolean
         *      ValueType::Datetime_local
         *      ValueType::Date
         *  The filter that is used is based on this ValueType
         */

        /*
         * Name << REQUIRED
         *  Name of variable of $_POST global
         */

        /*
         * checkIfExists
         *  Needs parameter of the name of the column it has to check.
         */

        /*
         * required
         * Needs a boolean parameter, not required
         */

        /*
         * maxLength
         *  Needs a integer parameter of the amount of tokens the string can have
         */

        /*
         * minLength
         *  Needs an integer parameter of the amount of tokens the string has to have
         */

        /*
         * dateTimeLocal
         * Boolean, But can be empty as well
         */

        /*
         * mustBeSelected
         * Boolean, must be true
         * To be used at select fields, if an option has to be selected keep the first option selected and disabled with a value of 0
         */

## Example

    function validator() {
        $validator = new Validator($this->db, $this->message, "tablename");

        $validator->setValidationRule([
            'type'     => ValueType::Email,
            'name'     => 'email',
            'required' => true
        ]);

        $validator->setValidationRule([
            'type'      => ValueType::String,
            'name'      => 'name',
            'minLength' => 2,
            'maxLength' => 90
        ]);

        /*
         * Execute validation and return to controller
         * @returns boolean
         */

        return $validator->validate();   
    }