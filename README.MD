Информация по установке

[Установка модуля]
Скопируйте в директорию applications/modules модуль, который располагается в директории под названием payqr. Структура модуля представлена ниже.

-\payqr
|
|-\assets
   |-\admin
      |-\index.html
      |-\payqr.tpl
   |-\css
   |-\js
      |-\admin.js
      |-\payqr.js
   .-index.tpl
|-\classes
      |-\payqr
           |-\classes
                |-.payqr_authentication.php
                |-.payqr_autoload.php
                |-.payqr_base.php
                |-.payqr_base_request.php
                |-.payqr_button.php
                |-.payqr_curl.php
                |-.payqr_curl_response.php
                |-.payqr_deliverycase.php
                |-.payqr_exeption.php
                |-.payqr_invoice.php
                |-.payqr_invoice_action.php
                |-.payqr_json_validator.php
                |-.payqr_logs.php
                |-.payqr_no_curl.php
                |-.payqr_pickpoint.php
                |-.payqr_product.php
                |-.payqr_receiver.php
                |-.payqr_revert.php
                |-.payqr_revert_action.php
           |-\handlers
                |-.invoice.cancelled.php
                |-.invoice.deliverycases.updating.php
                |-.invoice.failed.php
                |-.invoice.order.creating.php
                |-.invoice.paid.php
                |-.invoice.pickpoints.updating.php
                |-.invoice.reverted.php
                |-.revert.failed.php
                |-.revert.succeeded.php
           |-\handlers_errors
                |-.invoice_action_error.php
                |-.receiver_error.php
                |-.revert_action_error.php           
           .-payqr_config.php
|-\helpers
   .-payqr_helper.php 
|-\language
   |-\ru_RU
        |-\LC_MESSAGES
              -.payqr.mo
              -.payqr.po
|-\models
   -.cart_model.php
   -.delivery_model.php
   -.order_model.php
   -.payment_model.php
   -.payqr_model.php
   -.payqrbutton_model.php
   -.payqrreceiver_model.php
   -.product_model.php
.-admin.php
.-module_info.php
.-payqr.php


Перейдите в раздел управления сайтом и далее из меню "Модули" => "Все модули". Далее перейдите в раздел "Установить модули". Далее в списке модулей вы находите PayQR и устанавливаете модуль. После установки перейдите в раздел модули и активируете переключатели в 3-х позициях: автозагрузка, URL доступ, показывать в меню.

[Подключение JavaScript библиотеки]
В файле templates\main.tpl вставляем в раздел заголовка <head>… </head> строки из личного кабинета PayQR вида <script src="https://payqr.ru/popup.js?merchId={getMerchantId()}"></script>


[Настройка шаблонов]
•	Для категории товаров используем шаблон, расположенный по пути templates\fullMarket\components\TOpi\assets\one_product_item.tpl и вставляем следующую строку {echo getButton('category', $p->getId())} результат см. ниже (172 строка  в шаблоне):

            <div class="no-vis-table">
                {echo getButton('category', $p->getId())} <---- Ваш код
                <!-- Start. Collect information about Variants, for future processing -->

•	Для карточки товара в шаблоне templates\fullMarket\shop\product.tpl ищем строки и вставляем следующую строку {echo getButton('product', $model->getId())} результат см. ниже (стр. 164 в шаблоне)

                            </div>
                                {/if}
                                {echo getButton('product', $model->getId())} <---- Ваш код
                                <!-- End. Check variant-->
                                <div class="frame-prices-buy-wish-compare">

•	Для корзины товара в шаблоне templates\fullMarket\shop\cart_popup.tpl и вставляем следующую строку {echo getButton('cart')} результат см. на изображении
			<div class="btn-form f_l">
                <button type="button" data-closed="closed-js">
                    <span class="text-el">{lang('Вернуться к покупкам','fullMarket')}</span>
                </button>
            </div>
            {echo getButton('cart')} <---- Ваш код
            <div class="btn-buy f_r">
                <a href="{shop_url('cart')}">                                


[Интеграция модуля со способами доставки]

Важная особенность CMS обусловливается тем, что после добавления в CMS нового способа доставки, платежный сервис не определяет ее. Чтобы разрешить данную проблему, необходимо удалить и заново установить модуль.

[Настройка платежной системы]
	Перейдите в настройку модуля.

Дале в интерфейсе формы вам предстоит настроить кнопку и внести все необходимые реквизиты:
•	PayQR код магазина;
•	PayQR secret key in;
•	PayQR secret key out;
Перечисленные опции вы найдете в личном кабинете по адресу https://payqr.ru/biz/ в разделе "Магазины" -> "Взаимодействие".
Так же в разделе "Взаимодействие" внесите в поле "URL для уведомлений" строчку "URL PayQR обработчика" из формы настройки PayQR модуля на вашем сайте.
Внимание, чтобы увидеть лог работы модуля, откройте доступ на запись в корень сайта для файла payqr.log
Стоит учесть, что платежный сервис работает исключительно с российскими рублями, поэтому вам необходимо установить в качестве валюты по умолчанию "рубли". Для это вам стоит перейти в настройках сайта "Настройки" => "Валюты".
На момент разработки модуля, все ценообразущие механизмы в CMS работают по принципу преобразования основной валюты доллары США в рабочую валюту.