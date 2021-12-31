# View

All view files should be placed in { ***public/views*** }

The global template file can be found in { ***public/template/index.php*** }

The global template file and view files both always have the next variables:
* ***$stack*** { $stack has all data in one associative array, for each key value of $stack is a variable available. So you can get this variable with { ***$keyName*** }
* ***$template*** { >> Stil in development << Holds a Template object, this template object can help you render your data in the right formats. }
* ***$messages*** { Holds an Array of all type of messages, available keys are: ***errors***, ***notifications***, ***successs*** }
* ***$data*** { $data should be available when passed through a controller, this can be for example database records.
* ***$title*** { Holds the page title that will be put between the HTML title tags, should be set in the controller > default value is empty }
* ***$description*** { Holds the page description for the meta description tag > should be set in the controller > default value is empty}