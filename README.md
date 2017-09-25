## [Russian](#rus)
## [English](#en)

## Russian <a id="rus"></a>

### Внимание, пакет находится в активной стадии разработки. Примерное время релиза 27.09.17

Целью данной библиотеки стоит как можно больший охват возможностей vk api

Библиотека поддерживает большую часть доступных методов.


### Установка

*composer*

```php
composer require jorrygo/fullvk
```

### Авторизация

Поддерживается авторизация с помощью OAuth и с помощью логина/пароля

*OAuth*

```php
$vk = new \JorryGo\FullVk\Vk($client_id, $client_secret);
$link = $vk->getAuthLink('http://yourRedirectUri.com/');
```

Или с настройками доступа приложения и некой строкой, которая вернется на rediret uri

```php
$link = $vk->getAuthLink('http://yourRedirectUri.com/', 'friends,wall,groups', 'my data for return in redirect uri');
```

___Получение access token___

Для получения access token требуется передать redirect_uri который вы использовали 
при формировании ссылке а так же временный код полученный при авторизации пользователя

```php
$vk = new \JorryGo\FullVk\Vk($client_id, $client_secret);

$result = $vk->getAuthToken('http://yourRedirectUri.com/', $code);
```

В ответе вы получите обьект с вашим access token и id пользователя

___Авторизация по access token___

```php
$user = $vk->getUser($access_token, $user_id);
```

Кроме того, вы можете пропустить этот шаг и получить обьект пользователя сразу при получении токена

```php
$user = $vk->getAuthToken('http://yourRedirectUri.com/', $code, true);
```

*Авторизация по логину/паролю*

Для авторизации этим способом используются данные официального приложения vk под 
windows.

При использовании этого способа авторизации доступны обсолютно все методы vk-api,
в том числе методы для работы с личными сообщениями

```php
$vk = new \JorryGo\FullVk\Vk();
$user = $vk->password_authorization('username', 'password');
print_r($user->getProfileInfo());
```
###Вызов какого-либо метода
```php
$user->execute('method.name', ['param'=> 'value']);
```

### Краткий пример использования пакета

```php
$user->getProfileInfo(); //Возвращает информацию о текущем профиле
$user->getCounters(); //Возвращает ненулевые значения счетчиков пользователя. 
$user->banUser($user_id); //Добавляет пользователя в черный список. 
$user->unbanUser($user_id); //Удаляет пользователя из черного списка. 
$user->getBanned(); //Возвращает список пользователей, находящихся в черном списке.
$user->getBanned($offset = 0, $count = 20); //Возвращает список пользователей, находящихся в черном списке.

```

## In English <a id="en"></a>

sd