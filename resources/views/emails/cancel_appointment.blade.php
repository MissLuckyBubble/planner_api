<x-mail::message>
    <img src="https://files.fm/thumb_show.php?i=7rrj74vve" class="logo" alt="Icon">
    <br/>
    @if($appointment->status == 'Отказан от Фирмата')
        <p>
            <b>Здравейте {{$customer->name}},</b><br/>
            Вашият час при <b>{{$business->name}}</b>
            на {{ \Carbon\Carbon::parse($appointment->date)->format('d.m.Y') }}
            от {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i')}}
            беше отказан от тях.
        </p>
        <p>
            Статус на запазания час: <b>{{ $appointment->status }}</b><br/>
        <hr/>
        Ако смятате, че това е някаква грешка
        може да се свържете с тях на телефон:
        <a href="tel: {{ '+359' . $business->user->phoneNumber  }}">
            {{ '+359' . $business->user->phoneNumber  }}</a>
        </p>
    @else
        <p>
            <b>Здравейте {{$business->name}},</b><br/>
            Записаният час за клиента <b>{{$customer->name}}</b>
            на {{ \Carbon\Carbon::parse($appointment->date)->format('d.m.Y') }}
            от {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i')}}
            беше отказан от него.
        </p>
        <p>
            Статус на запазания час: <b>{{ $appointment->status }}</b>
        <hr/>
        Ако смятате, че това е някаква грешка
        може да се свържете с тях на телефон:
        <a href="tel: {{ '+359' . $customer->user->phoneNumber  }}">
            {{ '+359' . $customer->user->phoneNumber  }}</a>
        </p>
    @endif
    <p>Благодарим, че използвахте нашето приложение,<br />
       Екипът на {{ config('app.name') }}</p>
</x-mail::message>
