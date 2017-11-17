Yii Framework 2 AMQP plugin Change Log
=============================================

3.0.0 November 17, 2017
-----------------------

- Yii2 has been updated and yii\base\Object is now yii\base\BaseObject for compatibility with PHP 7.2.

2.2.1 June 30, 2016
-----------------------

- Do not override messageType in Client from Amqp.

2.2.0 June 30, 2016
-----------------------

- Ability to select message type for each queue.

2.1.1 June 30, 2016
-----------------------

- Extend all exceptions from Yii2 Base Exception.

2.1.0 June 30, 2016
-----------------------

- Removed explicit connection to avoid max connections overflow.

2.0.0 June 30, 2016
-----------------------

- AMQP client now presents as low level API.
- Added high level API (need to expand it and add AmqpController with Handlers).
- By default client will use serialization strategy for messages.

1.0.1 June 29, 2016
-----------------------

- Small fixes, Travis CI and cleanup.

1.0.0 June 29, 2016
-----------------------

- Initial release.
