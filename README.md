# Тестовое задание #

### Задание №1 ###

После успешной покупки билетов на событие, данные попадают в список заказов.
Список заказов сохраняется в таблице MySql в виде:


id  | event_id  | event_date          | ticket_adult_price  | ticket_adult_quantity  | ticket_kid_price  | ticket_kid_quantity  | barcode   | equal_price  | created
--- | --------- | ------------------- | ------------------- | ---------------------- | ----------------- | -------------------- | --------  | ------------ | -------------------
1   | 003       | 2021-08-21 13:00:00 | 700                 | 1                      | 450               | 0                    | 11111111  | 700          | 2021-01-11 13:22:09
2   | 006       | 2021-07-29 18:00:00 | 1000                | 0                      | 800               | 2                    | 22222222  | 1600         | 2021-01-12 16:62:08
3   | 003       | 2021-08-15 17:00:00 | 700                 | 4                      | 450               | 3                    | 33333333  | 4150         | 2021-01-13 10:08:45


Где:

* `id` - int(10) - инкрементальный порядковый номер заказа
* `event_id` - int(11) - уникальный ид события. У каждого события есть свое название, 
  описание, расписание, цены и свой уникальный `event_id` соответственно
* `event_date` - varchar(10) - дата и время на которое были куплены билеты
* `ticket_adult_price` - int(11) - цена взрослого билета на момент покупки
* `ticket_adult_quantity` - int(11) - количество купленных взрослых билетов в этом заказе
* `ticket_kid_price` - int(11) - цена детского билета на момент покупки
* `ticket_kid_quantity` - int(11) - количество купленных детских билетов в этом заказе
* `barcode` - varchar(120) - уникальный штрих код заказа
* `equal_price` - int(11) - общая сумма заказа
* `created` - datetime - дата создания заказа


**Задача: написать функцию, которая будет добавлять заказы в эту таблицу.**


Аргументы которые функция получает на входе:
`event_id`, `event_date`, `ticket_adult_price`, 
`ticket_adult_quantity`, `ticket_kid_price`, `ticket_kid_quantity` 


 Нужно сгенерировать `barcode`, который будет уникальным со 
 случайным набором цифр, он не должен быть порядковым. 

 Так же, существует некая сторонняя api.site.com. API писать не нужно,
возвращаемые данные можно замокать и возвращать в случайном порядке.
 в которой нужно сделать бронь заказа отправив ей (https://api.site.com/book)
`event_id`, `event_date`, `ticket_adult_price`,
`ticket_adult_quantity`, `ticket_kid_price`, `ticket_kid_quantity`,
`barcode`. На что она может вернуть
либо `{message: 'order successfully booked'}`,
либо `{error: 'barcode already exists'}`.
В случае если получаем ошибку,
нужно сгенерировать новый barcode и повторить попытку.
Важно учесть, если запрос будет происходить одновременно,
не должно возникнуть такой ситуации,
что двум разным заказам присвоился один номер.

После успешной брони, нужно отправить на стороннюю апи
запрос с подтверждением (https://api.site.com/approve),
который принимает только barcode.
Ответов может быть 2 варианта - успешный:
`{message: 'order successfully aproved'}`
и различные варианты ошибок `{error: 'event cancelled'}`,
`{error: 'no tickets'}`, `{error: 'no seats'}`,
`{error: 'fan removed'}`. В случае успеха, сохраняем заказ в БД


### Задание №2 ###

1.   Некоторые события нужно продавать с дополнительными типами билетов - льготный 
     и групповой, у которых будут свои цены и название.
     Имеется информация, что вероятно, будут другие типы билетов, 
     которые нужно будет добавить.
     Нужно уметь сохранять при заказе 2 дополнительных типа билета, 
     льготный и групповой в бд.
     **Задача** - Нормализовать таблицу учитывая добавленные типы билетов, 
     показать конечный вид таблицы. Объяснить свое решение.
2.   Часто посетители из одного заказа приходят не одновременно на события.
     Возникает необходимость проверять их билеты по отдельности.
     Для этого у каждого билета должен быть свой баркод.
     Если в одном заказе было куплено несколько билетов, 2 взрослых, 3 детских, 
     4 льготных - то должно быть 9 баркодов для каждого билета соответственно.
     **Задача** - Нормализовать таблицу, учитывая что у каждого билета свой баркод,
     показать конечный вид таблицы. Объяснить свое решение.

### Задание №3 ###

Сопроводить документацией своё решение.


---


**Задания выполнены в виде версий репозитория. Для каждой версии создан тег.**


