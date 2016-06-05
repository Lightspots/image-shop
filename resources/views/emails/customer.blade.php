<html>
<head>

</head>
<body>
Sehr geehrte(r) Herr/Frau {{$order->firstname}} {{$order->lastname}}
<br>
Besten Dank für Ihre Bestellung, wir werden Sie so schnell als möglich abarbeiten.

<h3>Zusammenfassung</h3>
<b>Kundendaten:</b><br>
{{$order->firstname}} {{$order->lastname}}<br>
{{$order->street}}<br>
{{$order->zip}} {{$order->city}}<br>
{{$order->phone}}<br>
{{$order->email}}
<br><br>
<b>Bemerkungen</b><br>
<span style="white-space: pre-line"><i>{{$order->remark or '-'}}</i></span>
<br><br>
<b>Album</b><br>
#{{$order->album->id}}: {{$order->album->name}}
<br><br>
<b>Bilder</b><br>
Papierqualität: @if ($order->finish == 'DULL')
                    matt
                @else
                    glänzend
                @endif
<ul>
@foreach ($order->photo as $photo)
    <li>Datei: {{$photo->name}}, Format: {{$photo->size}}, {{$photo->count}} Stk. für {{$photo->price}} CHF</li>
@endforeach
    <li>Versandkosten: {{$shippingCosts}} CHF</li>
</ul>
<b>Total: {{$order->price}} CHF</b>
<hr>
<i>Dies ist eine automatisch generierte E-Mail</i>
</body>
</html>