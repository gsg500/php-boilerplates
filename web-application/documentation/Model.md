# Models

Models can be found and created in **app/models**, models should have the singular name of a database table. 
The model takes care of all php logic like database transfers and validation.

The Model has to extend the base Model and should have a constant TABLENAME defined with the name of the database table.

Standard methods inside the models are:

* fetchAll()
* fetchSingle( $id )
* delete( $id )
* executeSafeQuery( $sql ) //For when you have a query that does not need input data. Just a quick way of checking the query and returning the data.