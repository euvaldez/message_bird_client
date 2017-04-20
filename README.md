Assignment for Message Bird
========================

I implement a website where I can send sms messages to any phone using MessageBird API.

What's inside?
--------------

The website has the following functionality:
  * Send SMS to one or more recipients by entering them comma separated eg. 0613456789, 0652347892
  * Accepts only dutch mobile numbers.
  * When an incoming message content/body is longer than 160 chars, split it into small messages 
    to be sent separately;
  * Validation of the input data;
  * Send directly one request per second and if there too many schedule them to be sent 
    a little later;