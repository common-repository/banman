=== BanMan ===
Contributors: Leonid Tushov
Donate link: 
Tags: 2016, ads, adsense, advert, advert manager, advertise, advertisement, advertiser, advertising, adverts, banner, banner manager, commercial, monetize, popular, rotate, rotator, sidebar, statistics, stats, widget
Requires at least: 4.4.0
Tested up to: 4.5.2
Stable tag: 1.0.1.0
License: GPLv2

Простой и удобный плагин для управления баннерами на сайте. Simple and easy to use banner management system on the site.

== Description ==
[See English version below]

Плагин позволяет организовать на сайте простую и удобную систему управления баннерами. 

= Возможности =

* В качестве баннеров можно использовать любые изображения в форматах: JPEG, PNG, GIF (+ Animated GIF), а также Flash (SWF).
* Также в качестве баннера можно использовать код (html, javascript, css)
* Имеется возможность задавать диапазон отображения баннера на сайте  ( с определенной начальной по конечную дату ).
* Отображение баннеров на сайте зависит от позиции их вывода. Имеются две предопределенные позиции, а также возможность создания неограниченного количества позиций через создание виджетов или вызов специальной функции bman_add_positions() в любом другом плагине или теме WordPress
* Возможна настройка ведения статистики баннеров в плане подсчета количества показов и кликов по ним
* Возможность размещения баннера как фонового изображения всей страницы сайта
* Возможность размещения баннера в виде Pop-up окна

------------------

This plugin allows you to organize the site easy and convenient banner management system.

= Features =

* As the banners you can use any image formats: JPEG, PNG, GIF (+ Animated GIF), and Flash (SWF).
* Also, as a banner, you can use the code (html, javascript, css)
* It is possible to specify a range of display on the site banner (with some initial on the end date).
* Displaying banners depends on their output the position of the site. There are two pre-defined positions, as well as the ability to create an unlimited number of products through the creation of widgets, or call a special function bman_add_positions () in any other WordPress plugin or theme
* You can configure banner of doing statistics in terms of counting the number of impressions and clicks on them
* The ability to place a banner as the background image all pages of the site
* The ability to place a banner in the form of Pop-up Window


== Installation ==
[See English version below]

1. Скачайте архив плагина BanMan и загрузите папку banman из него в директорию с плагинами /wp-content/plugins/ либо просто найдите плагин через поиск плагинов в WordPress
2. Активируйте плагин в разделе Плагины
3. Вся работа с плагином происходит через раздел "Управление баннерами" в меню WordPress

------------------

1. Download the archive and upload the plugin BanMan banman folder from it to the directory with plugins "/wp-content/plugins/" or just get the plug-in through the search plugins in WordPress
2. Activate the plugin in the Plugins section
3. All work with the plugin happens through a section "Banners management" in WordPress menu


== Upgrade Notice ==

To update the plugin to the latest version simply click on the "Update Now" in the "Plugins" in front of the plugin "BanMan" or replace the contents of the folder "/wp-content/plugins/banman" on the contents of the archive "banman.zip"

== Screenshots ==

1. Страница создания нового баннера
2. Page to create a new banner
3. Страница управления баннерами (Список баннеров)
4. List of banners
5. Виджет для создании новой позиции вывода баннера
6. Widget for creating a new position of the output banner
7. Пример Pop-up баннера
8. Example Pop-up banner


== Frequently Asked Questions ==
[See English version below]

Вопрос: Как сделать так чтобы баннер начал отображаться не сразу после создания, а с определенной даты?
Ответ: Для этого в настройках баннера нужно указать в секции "Период показа" в поле "от" дату начала показа

Вопрос: Как добавить новую позицию для вывода баннера на сайте?
Ответ: Сделать это можно двумя способами. Самый простой это использовать виджеты. Перейдите в раздел Внешний вид / Виджеты и добавьте в нужную область новый виджет типа "Вывод баннера". В настройках виджета Вы можете указать наименование позиции вывода баннеров, а также максимальную ширину и высоту для баннеров в данной позиции. Также вы можете добавить новую позицию при помощи вызова функции bman_add_positions() - более подробно читайте ниже.

Вопрос: Как добавить новую позицию для вывода баннеров в нужном месте шаблона (темы) WordPress?
Ответ: Это можно сделать при помощи вызова специальной функции плагина: "bman_add_positions". Правильный формат вызова функции выглядит так: <?php if (function_exists('bman_add_positions')) { bman_add_positions(array('top-head'=>'В шапке', 'under-post'=>'Под текстом поста')); } ?>. Далее в нужных местах нужно вызвать функцию "bman_banners" в таком формате: <?php if (function_exists('bman_banners')) bman_banners('under-post'); ?>

Вопрос: Где можно включить и увидеть статистику по показам и кликам по баннеру?
Ответ: Включить ститистику можно в настройках баннера (индивидуально). Кол-во просмотров и кликов по баннерам отображается в списке баннеров (смотрите скриншот №3)

Вопрос: Почему не отображаются мои баннеры в админке и (или) на сайте?
Ответ: Если вы используете Google Chrome, убедитесь в том что его расширение AdBlock не заблокировало содержимое на страницах сайта, а также в админке. В противном случае добавьте исключение для вашего сайта в настройки AdBlock.

Пожалуйста, направляйте все вопросы по адресу: mail@tushov.ru

--------------------------------------------------------------------------

Question: How can I make a banner to be displayed is not started immediately after its creation, and on a certain date?
Answer: To do this, you need to specify the settings of the banner in the "Delivery Period" in the field "from" start date

Question: How to add a new position to display a banner on the site?
Answer: You can do this in two ways. The easiest is to use the widgets. Go to Appearance / Widgets and add the desired area of ​​the new widget type "Output banner." In the setting of the widget, you can specify the name of the position display banners, as well as the maximum width and height for the banner in this position. You can also add a new position by calling bman_add_positions function - read more detail below.

Question: How to add a new position to display banners in the right place a template (theme) WordPress?
Answer: This can be done by calling a special plug-in functions: "bman_add_positions". The correct format function call looks like this: <?php if (function_exists('bman_add_positions')) { bman_add_positions(array('top-head'=>'In Top Head', 'under-post'=>'Under post text')); } ?>. Further, in the right places you need to call the function "bman_banners" in this format: <?php if (function_exists('bman_banners')) bman_banners('under-post'); ?>

Question: Where can I turn and see the statistics for impressions and clicks on a banner?
Answer: Enable the statistics for the banner can be in the settings (individually). Views and clicks on banners displayed in the banners list (see screenshot №4)

Question: Why does not my banners displayed in the admin area and (or) on the site?
Answer: If you are using Google Chrome, make sure that it is not blocked by AdBlock extension of the content on the site, as well as in the admin area. Otherwise, add an exception for your site in AdBlock settings.


Please, send all questions to mail@tushov.ru


== Changelog ==

= 1.00 =
* Initial release

= 1.1 =
* Fixed banner editing
* Added support for using Flash (swf) as banners
* Added the ability to specify the size of the banner in its settings