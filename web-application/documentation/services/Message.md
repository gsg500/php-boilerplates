# Message

The message service creates messages and creates a session to store the values.
The service only has one method that you need because the render method in the controller makes sure error messages are always being send to the view.

The types of messages available are:

* Error //When something is going terribly wrong
* Notification //When something could not be achieved but it is'nt a big problem.
* Success //When something is successful. 

## How to use

In the model, the service is reached by calling **$this->message**

In the controller, the service reached by calling **$this->model->message**

The method **createMessage( MessageType $type, String $message )** is called to create a message.
The MessageType class has some defined constants just to keep the code neat and prevent syntax errors.
