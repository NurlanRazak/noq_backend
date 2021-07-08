@component('mail::message')

# Чек - {{ $order->id }}

This is a sentence

This is a sentence with **bolded text**.

@component('mail::table')
| Наименование      | Сумма     |
| ------------------|----------:|
| Сумма заказа      | {{ $order->total_amount }}     |

@endcomponent

Время: {{ $order->created_at }}

Спасибо,
{{ config('app.name') }}
@endcomponent
