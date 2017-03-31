Assignment for Message Bird
========================

In this assignment I implement a website where I can send sms messages to my phone using the API
provided by the company.

What's inside?
--------------

The website has the following functionality:

  * Accepts SMS messages that are sent to message bird API;

  * When an incoming message content/body is longer than 160 chars, split it into multiple parts;

  * Empty or incorrect messages are not accepted;

  * Can only send one request per second;

  * Make sure that multiple incoming messages are queued for a short while.