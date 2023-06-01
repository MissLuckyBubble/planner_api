<x-mail::message>
    <img src="https://files.fm/thumb_show.php?i=7rrj74vve" class="logo"
         alt="Icon">
    <br>
    <b>Здравейте {{$customer->name}},</b><br/>
        Успешно запазихте час при <b>{{$business->name}}</b>.
    <hr>
    <p>
        Данни за запазения час: <br/>
        Име на фирмата: {{ $business->name}}<br/>
        Адрес:{{$address->description}}<br/>
        Дата: {{ $appointment->date->format('d.m.Y') }}<br/>
        Начален час: {{ $appointment->start_time->format('H:i')}}<br/>
        Избраните от вас услуги са:
    <ul>
        @foreach ($appointment->services as $service)
            <li>{{ $service->title}} - {{ $service->price}} лв. ({{$service->duration_minutes}}мин.)</li>
        @endforeach
    </ul>
    Обща сума: {{$appointment->total_price}} лв.<br>
    Общо времетраене: {{$appointment->duration}} мин.
    <hr>
    <p>
        Ако е необходимо да се свържете с {{ $business->name}},
        може да използвате телефонен номер:
        <a href="tel: {{ '+359' . $business->user->phoneNumber  }}">
            {{ '+359' . $business->user->phoneNumber  }}</a>
    </p>
    <p>
        Благадарим, че използвахте нашето приложение,<br>
        Екипът на {{ config('app.name') }}
    </p>
</x-mail::message>
