@component('mail::table')
| Item       | Quantidade   | Preço  |
| ---------- |:------------:| ------:|
@foreach ($data['items'] as $item)
| {{$item['title']}} | {{$item['quantity']}} | R$ {{$item['unit_price']/100}} |
@endforeach
@endcomponent