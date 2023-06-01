<x-mail::message>
    <img src="https://files.fm/thumb_show.php?i=7rrj74vve" class="logo"
         alt="Icon">
    <br>
    <b>Здравейте {{$customer->name}},</b><br/>
    @if(\Carbon\Carbon::parse($appointment->date)->format('d.m.Y') === \Carbon\Carbon::today()->format('d.m.Y'))
        Искаме да ви напомним за предостящия Ви час днес,
    @else
        Искаме да ви напомним за предстоящия Ви час утре,
    @endif
    {{ \Carbon\Carbon::parse($appointment->date)->format('d.m.Y') }} в {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i')}} часа,
    при <b>{{$business->name}}</b>, {{$address->description}}.
    Услугите които сте запазили са: <br/>
    <ul>
        @foreach ($appointment->services as $service)
            <li>{{ $service->title}} - {{ $service->price}} лв. ({{$service->duration_minutes}}мин.)</li>
        @endforeach
    </ul>
    Обща сума: {{$appointment->total_price}} лв.<br>
    Общо времетраене: {{$appointment->duration}} мин.
    <hr>
    <p>Ако се налага да отмените запазения час, може да го направите в раздела "Моите часове"
        в нашето приложение. </p>
    <p>
        Ако е необходимо да се свържете с {{ $business->name}},
        може да използвате телефонен номер:
        <a href="tel: {{ '+359' . $business->user->phoneNumber  }}">
            {{ '+359' . $business->user->phoneNumber  }}</a>
    </p>
    <p>
        Благадарим, че използвахте нашето приложение,<br>
        Eкипът на {{ config('app.name') }}
    </p>
</x-mail::message>
