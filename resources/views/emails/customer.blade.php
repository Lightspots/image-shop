<html>
<head>

</head>
<body>
Sehr geehrte(r) Herr/Frau {{$firstname}} {{$lastname}}

Besten Dank für Ihre Bestellung, wir werden Sie so schnell als möglich abarbeiten.

<h3>Zusammenfassung</h3>
Album: {{$album['name']}}
<ul>
@foreach ($photos as $photo)
    <li>Datei: {{$photo['name']}}, Format: {{$photo['size']}}, {{$photo['count']}} Stk. für {{$photo['price']}}</li>
@endforeach
</ul>
<b>Total: {{$price}}</b>
<hr>
<i>Dies ist eine automatisch generierte E-Mail</i>
</body>
</html>