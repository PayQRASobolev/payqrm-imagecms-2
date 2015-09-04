<div class="container">
    <section class="mini-layout">
        <div class="frame_title clearfix">
            <div class="pull-left">
                <span class="help-inline"></span>
                <span class="title">{lang('PayQR module', 'payqr')} {if $isRUR == FALSE}<span style="color:red;">Модуль работает с валютой рубль. Установите валюту в настройках</span>{/if}</span>
            </div>
            <div class="pull-right">
                <div class="d-i_b">
                    <a href="{$BASE_URL}/admin/components/modules_table"
                       class="t-d_n m-r_15 pjax">
                        <span class="f-s_14">←</span>
                        <span class="t-d_u">{lang('Back', 'admin')}</span>
                    </a>
                    <!--a class="btn btn-small pjax" href="{$BASE_URL}">
                        <i class="icon-wrench"></i>
                    </a-->
                </div>
            </div>
        </div>

        <div class="m-t_15">
            <form action="/admin/components/init_window/payqr/save_button" method="post" name="data" id="save_payqr_form">
            <table class="table  table-bordered table-hover table-condensed content_big_td stat-wish-out">
                <thead>
                    <tr>
                        <th>Название свойства</th>
                        <th>Значение свойства</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PayQR merchant ID:</td>
                        <td><input type="text" name="payqr_merchant_id" class="field" value="{echo $entity->payqr_merchant_id}" size="40"></td>
                    </tr>
                    <tr>
                        <td>PayQR SecretKeyIn:</td>
                        <td><input type="text" name="payqr_merchant_secret_key_in" class="field" value="{echo $entity->payqr_merchant_secret_key_in}" size="40"></td>
                    </tr>
                    <tr>
                        <td>PayQR SecretKeyOut:</td>
                        <td><input type="text" name="payqr_merchant_secret_key_out" class="field" value="{echo $entity->payqr_merchant_secret_key_out}" size="40"></td>
                    </tr>
                    <tr>
                        <td>URL PayQR обработчика:</td>
                        <td><input type="text" name="payqr_hook_handler_url" class="field" value="{echo $entity->payqr_hook_handler_url}" size="40" disabled></td>
                    </tr>
                    <tr>
                        <td>URL PayQR логов:</td>
                        <td><input type="text" name="payqr_log_url" class="field" value="{echo $entity->payqr_log_url}" size="40" disabled></td>
                    </tr>
                    
                    <tr><td colspan="2"><hr></td></tr>

                    <tr>
                        <td>Показывать кнопку PayQR на страничке корзины:</td>
                        <td>
                            <select name="payqr_button_show_on_cart">
                                <option value='yes' {if $entity->payqr_button_show_on_cart=='yes'} selected {/if}>Да</option>
                                <option value='no' {if $entity->payqr_button_show_on_cart=='no'} selected {/if}>Нет</option>
                            </select>
                        </td>
                    </tr>

                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Цвет кнопки на странице корзины товаров:</td>
                            <td>
                                <select name="payqr_cart_button_color">
                                    <option value="default" {if $entity->payqr_cart_button_color=="default"} selected{/if}>По умолчанию</option>
                                    <option value="green" {if $entity->payqr_cart_button_color=="green"} selected {/if}>Зеленый</option>
                                    <option value="red" {if $entity->payqr_cart_button_color=="red"} selected {/if}>Красный</option>
                                    <option value="blue" {if $entity->payqr_cart_button_color=="blue"} selected {/if}>Синий</option>
                                    <option value="orange" {if $entity->payqr_cart_button_color=="orange"} selected {/if}>Оранжевый</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Форма кнопки на странице корзины товаров:</td>
                            <td>
                                <select name="payqr_cart_button_form">
                                    <option value="default" {if $entity->payqr_cart_button_form=="default"} selected{/if}>По умолчанию</option>
                                    <option value="sharp" {if $entity->payqr_cart_button_form=="sharp"} selected{/if}>Без округления</option>
                                    <option value="rude" {if $entity->payqr_cart_button_form=="rude"} selected{/if}>Минимальное округление</option>
                                    <option value="soft" {if $entity->payqr_cart_button_form=="soft"} selected{/if}>Мягкое округление</option>
                                    <option value="sleek" {if $entity->payqr_cart_button_form=="sleek"} selected{/if}>Значительное округление</option>
                                    <option value="oval" {if $entity->payqr_cart_button_form=="oval"} selected{/if}>Максимальное округление</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Тень кнопки на странице корзины товаров:</td>
                            <td>
                                <select name="payqr_cart_button_shadow">
                                    <option value="default" {if $entity->payqr_cart_button_shadow=="default"} selected{/if}>По умолчанию</option>
                                    <option value="shadow" {if $entity->payqr_cart_button_shadow=="shadow"} selected{/if}>Включена</option>
                                    <option value="noshadow" {if $entity->payqr_cart_button_shadow=="noshadow"} selected{/if}>Отключена</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Градиент кнопки на странице корзины товаров:</td>
                            <td>
                                <select name="payqr_cart_button_gradient">
                                    <option value="default" {if $entity->payqr_cart_button_gradient=="default"} selected{/if}>По умолчанию</option>
                                    <option value="flat" {if $entity->payqr_cart_button_gradient=="flat"} selected{/if}>Отключен</option>
                                    <option value="gradient" {if $entity->payqr_cart_button_gradient=="gradient"} selected{/if}>Включен</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Размер шрифта кнопки на странице корзины товаров:</td>
                            <td>
                                <select name="payqr_cart_button_font_trans">
                                    <option value="default" {if $entity->payqr_cart_button_font_trans=="default"} selected{/if}>По умолчанию</option>
                                    <option value="text-small" {if $entity->payqr_cart_button_font_trans=="text-small"} selected{/if}>Мелко</option>
                                    <option value="text-medium" {if $entity->payqr_cart_button_font_trans==text-"medium"} selected{/if}>Средне</option>
                                    <option value="text-large" {if $entity->payqr_cart_button_font_trans=="text-large"} selected{/if}>Крупно</option>
                                </select>
                            </td>
                        </tr>

                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Жирный шрифт текста кнопки на странице корзины товаров:</td>
                            <td>
                                <select name="payqr_cart_button_font_width">
                                    <option value="default" {if $entity->payqr_cart_button_font_width=="default"} selected{/if}>По умолчанию</option>
                                    <option value="text-normal" {if $entity->payqr_cart_button_font_width=="text-normal"} selected{/if}>Отключен</option>
                                    <option value="text-bold" {if $entity->payqr_cart_button_font_width=="text-bold"} selected{/if}>Включен</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Регистр текста кнопки на странице корзины товара:</td>
                            <td>
                                <select name="payqr_cart_button_text_case">
                                    <option value="default" {if $entity->payqr_cart_button_text_case=="default"} selected{/if}>По умолчанию</option>
                                    <option value="text-lowercase" {if $entity->payqr_cart_button_text_case=="text-lowercase"} selected{/if}>Нижний</option>
                                    <option value="text-standartcase" {if $entity->payqr_cart_button_text_case=="text-standartcase"} selected{/if}>Стандартный</option>
                                    <option value="text-uppercase" {if $entity->payqr_cart_button_text_case=="text-uppercase"} selected{/if}>Верхний</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Высота кнопки на странице корзины товаров:</td>
                            <td>
                                <input type="text" name="payqr_cart_button_height" class="field" value="{if $entity->payqr_cart_button_height==""}auto{/if}{if $entity->payqr_cart_button_height!=""}{echo $entity->payqr_cart_button_height}{/if}" size="40">
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_cart=='no'} style="display:none;" {/if}>
                            <td>Ширина кнопки на странице корзины товаров:</td>
                            <td>
                                <input type="text" name="payqr_cart_button_width" class="field" value="{if $entity->payqr_cart_button_width==""}auto{/if}{if $entity->payqr_cart_button_width!=""}{echo $entity->payqr_cart_button_width}{/if}" size="40">
                            </td>
                        </tr>
                    
                    <tr><td colspan="2"><hr></td></tr>

                    <tr>
                        <td>Показывать кнопку PayQR на страничке карточки товаров:</td>
                        <td>
                            <select name="payqr_button_show_on_product">
                                <option value='yes' {if $entity->payqr_button_show_on_product=='yes'} selected{/if}>Да</option>
                                <option value='no' {if $entity->payqr_button_show_on_product=='no'} selected{/if}>Нет</option>
                            </select>
                        </td>
                    </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Цвет кнопки на странице карточки товаров:</td>
                            <td>
                                <select name="payqr_product_button_color">
                                    <option value="default" {if $entity->payqr_product_button_color=="default"} selected{/if}>По умолчанию</option>
                                    <option value="green" {if $entity->payqr_product_button_color=="green"} selected{/if}>Зеленый</option>
                                    <option value="red" {if $entity->payqr_product_button_color=="red"} selected{/if}>Красный</option>
                                    <option value="blue" {if $entity->payqr_product_button_color=="blue"} selected{/if}>Синий</option>
                                    <option value="orange" {if $entity->payqr_product_button_color=="orange"} selected{/if}>Оранжевый</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Форма кнопки на странице карточки товаров:</td>
                            <td>
                                <select name="payqr_product_button_form">
                                    <option value="default" {if $entity->payqr_product_button_form=="default"} selected{/if}>По умолчанию</option>
                                    <option value="sharp" {if $entity->payqr_product_button_form=="sharp"} selected{/if}>Без округления</option>
                                    <option value="rude" {if $entity->payqr_product_button_form=="rude"} selected{/if}>Минимальное округление</option>
                                    <option value="soft" {if $entity->payqr_product_button_form=="soft"} selected{/if}>Мягкое округление</option>
                                    <option value="sleek" {if $entity->payqr_product_button_form=="sleek"} selected{/if}>Значительное округление</option>
                                    <option value="oval" {if $entity->payqr_product_button_form=="oval"} selected{/if}>Максимальное округление</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Тень кнопки на странице карточки товаров:</td>
                            <td>
                                <select name="payqr_product_button_shadow">
                                    <option value="default" {if $entity->payqr_product_button_shadow=="default"} selected{/if}>По умолчанию</option>
                                    <option value="shadow" {if $entity->payqr_product_button_shadow=="shadow"} selected{/if}>Включена</option>
                                    <option value="noshadow" {if $entity->payqr_product_button_shadow=="noshadow"} selected{/if}>Отключена</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Градиент кнопки на странице карточки товаров:</td>
                            <td>
                                <select name="payqr_product_button_gradient">
                                    <option value="default" {if $entity->payqr_product_button_gradient=="default"} selected{/if}>По умолчанию</option>
                                    <option value="flat" {if $entity->payqr_product_button_gradient=="flat"} selected{/if}>Отключен</option>
                                    <option value="gradient" {if $entity->payqr_product_button_gradient=="gradient"} selected{/if}>Включен</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Размер шрифта кнопки на странице карточки товаров:</td>
                            <td>
                                <select name="payqr_product_button_font_trans">
                                    <option value="default" {if $entity->payqr_product_button_font_trans=="default"} selected{/if}>По умолчанию</option>
                                    <option value="text-small" {if $entity->payqr_product_button_font_trans=="text-small"} selected{/if}>Мелко</option>
                                    <option value="text-medium" {if $entity->payqr_product_button_font_trans=="text-medium"} selected{/if}>Средне</option>
                                    <option value="text-large" {if $entity->payqr_product_button_font_trans=="text-large"} selected{/if}>Крупно</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Жирный шрифт текста кнопки на странице карточки товаров:</td>
                            <td>
                                <select name="payqr_product_button_font_width">
                                    <option value="default" {if $entity->payqr_product_button_font_width=="default"} selected{/if}>По умолчанию</option>
                                    <option value="text-normal" {if $entity->payqr_product_button_font_width=="text-normal"} selected{/if}>Отключен</option>
                                    <option value="text-bold" {if $entity->payqr_product_button_font_width=="text-bold"} selected{/if}>Включен</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Регистр текста кнопки на странице карточки товара:</td>
                            <td>
                                <select name="payqr_product_button_text_case">
                                    <option value="default" {if $entity->payqr_product_button_text_case=="default"} selected{/if}>По умолчанию</option>
                                    <option value="text-lowercase" {if $entity->payqr_product_button_text_case=="text-lowercase"} selected{/if}>Нижний</option>
                                    <option value="text-standartcase" {if $entity->payqr_product_button_text_case=="text-standartcase"} selected{/if}>Стандартный</option>
                                    <option value="text-uppercase" {if $entity->payqr_product_button_text_case=="text-uppercase"} selected{/if}>Верхний</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Высота кнопки на странице карточки товаров:</td>
                            <td>
                                <input type="text" name="payqr_product_button_height" class="field" value="{if $entity->payqr_product_button_height==""}auto{/if}{if $entity->payqr_product_button_height!=""}{echo $entity->payqr_product_button_height}{/if}" size="40">
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_product=='no'} style="display:none;" {/if}>
                            <td>Ширина кнопки на странице карточки товаров:</td>
                            <td>
                                <input type="text" name="payqr_product_button_width" class="field" value="{if $entity->payqr_product_button_width==""}auto{/if}{if $entity->payqr_product_button_width!=""}{echo $entity->payqr_product_button_width}{/if}" size="40">
                            </td>
                        </tr>
                    
                    <tr><td colspan="2"><hr></td></tr>

                    <tr>
                        <td>Показывать кнопку PayQR на страничке категории товаров:</td>
                        <td>
                            <select name="payqr_button_show_on_category">
                                <option value='yes' {if $entity->payqr_button_show_on_category=='yes'}selected{/if}>Да</option>
                                <option value='no' {if $entity->payqr_button_show_on_category=='no'}selected{/if}>Нет</option>
                            </select>
                        </td>
                    </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Цвет кнопки на странице категории товаров:</td>
                            <td>
                                <select name="payqr_category_button_color">
                                    <option value="default" {if $entity->payqr_category_button_color=="default"}selected{/if}>По умолчанию</option>
                                    <option value="green" {if $entity->payqr_category_button_color=="green"}selected{/if}>Зеленый</option>
                                    <option value="red" {if $entity->payqr_category_button_color=="red"}selected{/if}>Красный</option>
                                    <option value="blue" {if $entity->payqr_category_button_color=="blue"}selected{/if}>Синий</option>
                                    <option value="orange" {if $entity->payqr_category_button_color=="orange"}selected{/if}>Оранжевый</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Форма кнопки на странице категории товаров:</td>
                            <td>
                                <select name="payqr_category_button_form">
                                    <option value="default" {if $entity->payqr_category_button_form=="default"}selected{/if}>По умолчанию</option>
                                    <option value="sharp" {if $entity->payqr_category_button_form=="sharp"}selected{/if}>Без округления</option>
                                    <option value="rude" {if $entity->payqr_category_button_form=="rude"}selected{/if}>Минимальное округление</option>
                                    <option value="soft" {if $entity->payqr_category_button_form=="soft"}selected{/if}>Мягкое округление</option>
                                    <option value="sleek" {if $entity->payqr_category_button_form=="sleek"}selected{/if}>Значительное округление</option>
                                    <option value="oval" {if $entity->payqr_category_button_form=="oval"}selected{/if}>Максимальное округление</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Тень кнопки на странице категории товаров:</td>
                            <td>
                                <select name="payqr_category_button_shadow">
                                    <option value="default" {if $entity->payqr_category_button_shadow=="default"}selected{/if}>По умолчанию</option>
                                    <option value="shadow" {if $entity->payqr_category_button_shadow=="shadow"}selected{/if}>Включена</option>
                                    <option value="noshadow" {if $entity->payqr_category_button_shadow=="noshadow"}selected{/if}>Отключена</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Градиент кнопки на странице категории товаров:</td>
                            <td>
                                <select name="payqr_category_button_gradient">
                                    <option value="default" {if $entity->payqr_category_button_gradient=="default"}selected{/if}>По умолчанию</option>
                                    <option value="flat" {if $entity->payqr_category_button_gradient=="flat"}selected{/if}>Отключен</option>
                                    <option value="gradient" {if $entity->payqr_category_button_gradient=="gradient"}selected{/if}>Включен</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Размер шрифта кнопки на странице категории товаров:</td>
                            <td>
                                <select name="payqr_category_button_font_trans">
                                    <option value="default" {if $entity->payqr_category_button_font_trans=="default"}selected{/if}>По умолчанию</option>
                                    <option value="text-small" {if $entity->payqr_category_button_font_trans=="text-small"}selected{/if}>Мелко</option>
                                    <option value="text-medium" {if $entity->payqr_category_button_font_trans=="text-medium"}selected{/if}>Средне</option>
                                    <option value="text-large" {if $entity->payqr_category_button_font_trans=="text-large"}selected{/if}>Крупно</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Жирный шрифт текста кнопки на странице категории товаров:</td>
                            <td>
                                <select name="payqr_category_button_font_width">
                                    <option value="default" {if $entity->payqr_category_button_font_width=="default"}selected{/if}>По умолчанию</option>
                                    <option value="text-normal" {if $entity->payqr_category_button_font_width=="text-normal"}selected{/if}>Отключен</option>
                                    <option value="text-bold" {if $entity->payqr_category_button_font_width=="text-bold"}selected{/if}>Включен</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Регистр текста кнопки на странице карточки товара:</td>
                            <td>
                                <select name="payqr_category_button_text_case">
                                    <option value="default" {if $entity->payqr_category_button_text_case=="default"}selected{/if}>По умолчанию</option>
                                    <option value="text-lowercase" {if $entity->payqr_category_button_text_case=="text-lowercase"}selected{/if}>Нижний</option>
                                    <option value="text-standartcase" {if $entity->payqr_category_button_text_case=="text-standartcase"}selected{/if}>Стандартный</option>
                                    <option value="text-uppercase" {if $entity->payqr_category_button_text_case=="text-uppercase"}selected{/if}>Верхний</option>
                                </select>
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Высота кнопки на странице категории товаров:</td>
                            <td>
                                <input type="text" name="payqr_category_button_height" class="field" value="{if $entity->payqr_category_button_height==""}auto{/if}{if $entity->payqr_category_button_height!=""}{echo $entity->payqr_category_button_height}{/if}" size="40">
                            </td>
                        </tr>
                        <tr {if $entity->payqr_button_show_on_category=='no'} style="display:none;" {/if}>
                            <td>Ширина кнопки на странице категории товаров:</td>
                            <td>
                                <input type="text" name="payqr_category_button_width" class="field" value="{if $entity->payqr_category_button_width==""}auto{/if}{if $entity->payqr_category_button_width!=""}{echo $entity->payqr_category_button_width}{/if}" size="40">
                            </td>
                        </tr>
                    
                    <tr><td colspan="2"><hr></td></tr>
                    <!--
                    <tr>
                        <td>Статус PayQR заказа "создан":</td>
                        <td>
                            <input type="text" name="payqr_status_creatted" class="field" value="{echo $entity->payqr_status_creatted}" size="40">
                        </td>
                    </tr>
                    <tr>
                        <td>Статус PayQR заказа "оплачен":</td>
                        <td>
                            <input type="text" name="payqr_status_paid" class="field" value="{echo $entity->payqr_status_paid}" size="40">
                        </td>
                    </tr>
                    <tr>
                        <td>Статус PayQR заказа "отменен":</td>
                        <td>
                            <input type="text" name="payqr_status_cancelled" class="field" value="{echo $entity->payqr_status_cancelled}" size="40">
                        </td>
                    </tr>
                    <tr>
                        <td>Статус PayQR заказа "завершен":</td>
                        <td>
                            <input type="text" name="payqr_status_completed" class="field" value="{echo $entity->payqr_status_completed}" size="40">
                        </td>
                    </tr>
                    -->
                    <input type="hidden" value="1" name="payqr_status_creatted">
                    <input type="hidden" value="1" name="payqr_status_paid">
                    <input type="hidden" value="0" name="payqr_status_cancelled">
                    <input type="hidden" value="2" name="payqr_status_completed">

                    <tr>
                        <td>Запрашивать имя покупателя:</td>
                        <td>
                            <select name="payqr_require_firstname">
                                <option value="default" {if $entity->payqr_require_firstname=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_firstname=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_firstname=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Запрашивать фамилию покупателя:</td>
                        <td>
                            <select name="payqr_require_lastname">
                                <option value="default" {if $entity->payqr_require_lastname=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_lastname=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_lastname=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Запрашивать отчество покупателя:</td>
                        <td>
                            <select name="payqr_require_middlename">
                                <option value="default" {if $entity->payqr_require_middlename=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_middlename=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_middlename=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Запрашивать номер телефона покупателя:</td>
                        <td>
                            <select name="payqr_require_phone">
                                <option value="default" {if $entity->payqr_require_phone=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_phone=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_phone=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Запрашивать заполнения e-mail покупателем:</td>
                        <td>
                            <select name="payqr_require_email" disabled>
                                <option value="default" {if $entity->payqr_require_email=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_email=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_email=="required"}selected{/if}selected>Запрашивать</option>
                            </select>
                            <input type="hidden" name="payqr_require_email" value="required" />
                        </td>
                    </tr>
                    <tr>
                        <td>Запрашивать адрес доставки:</td>
                        <td>
                            <select name="payqr_require_delivery" disabled>
                                <option value="default" {if $entity->payqr_require_delivery=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_delivery=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_delivery=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                        <input type="hidden" name="payqr_require_delivery" value="required" />
                    </tr>
                    <tr>
                        <td>Могут ли быть в магазине способы доставки:</td>
                        <td>
                            <select name="payqr_require_deliverycases" disabled>
                                <option value="default" {if $entity->payqr_require_deliverycases=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_deliverycases=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_deliverycases=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                        <input type="hidden" name="payqr_require_deliverycases" value="required" />
                    </tr>
                    <tr>
                        <td>Могут ли быть в магазине точки самовывоза:</td>
                        <td>
                            <select name="payqr_require_pickpoints" disabled>
                                <option value="default" {if $entity->payqr_require_pickpoints=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_pickpoints=="deny"}selected{/if} selected>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_pickpoints=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                        <input type="hidden" name="payqr_require_pickpoints" value="deny" />
                    </tr>
                    <tr>
                        <td>Предлагать ввести промо-идентификатор:</td>
                        <td>
                            <select name="payqr_require_promo">
                                <option value="default" {if $entity->payqr_require_promo=="default"}selected{/if}>По умолчанию</option>
                                <option value="deny" {if $entity->payqr_require_promo=="deny"}selected{/if}>Не запрашивать</option>
                                <option value="required" {if $entity->payqr_require_promo=="required"}selected{/if}>Запрашивать</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Текстовое название промо-идентификатора:</td>
                        <td><input type="text" name="payqr_promo_code" class="field" value="{echo $entity->payqr_promo_code}" size="40"></td>
                    </tr>
                    <tr>
                        <td>Сообщение в покупке после ее совершения:</td>
                        <td><input type="text" name="payqr_user_message_text" class="field" value="{echo $entity->payqr_user_message_text}" size="40"></td>
                    </tr>
                    <tr>
                        <td>URL изображения в покупке после ее совершения:</td>
                        <td><input type="text" name="payqr_user_message_imageurl" class="field" value="{echo $entity->payqr_user_message_imageurl}" size="40"></td>
                    </tr>
                    <tr>
                        <td>URL ссылка на сайта продавца в покупке после ее совершения:</td>
                        <td><input type="text" name="payqr_user_message_url" class="field" value="{echo $entity->payqr_user_message_url}" size="40"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" class="btn btn-small btn-primary formSubmit" data-form="#save_payqr_form" data-submit="" data-action="save"><i class="icon-ok icon-white"></i>{lang('Save', 'admin')}</button>
            </form>
        </div>
    </section>
</div>