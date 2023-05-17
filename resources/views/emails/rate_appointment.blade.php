<x-mail::message>
    <img src="https://files.fm/thumb_show.php?i=7rrj74vve" class="logo" alt="Icon">
    <br/>
    <p>
        <b>Здравейте {{$customer->name}},</b><br />
       Тъй като вашия час при {{$business->name}} e приключил на
        {{ \Carbon\Carbon::parse($appointment->date)->format('d.m.Y') }}г.
        {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i')}}ч.
        Искаме да Ви помолим да споделите Вашето мнение за посщението при тях!<br />
        <hr/>
    Вашето мнение е много важно както за нас, така и за бизнесът.
    То ще помогне за по-добро обслужване в бъдеще.<br />
    Можете да оцените {{$business->name}} и да оставите коментар, като посетите раздела "Моите минали часове"
    в нашето приложение. <br />
    Вашите коментар и оценка ще бъдат видими на страницата на {{$business->name}}. <br />
    Така техните бъдещи клиенти ще могат по-лесно да преценят да ли са подходащи да роботят заедно. <br />
    <hr/>
    Отново благодарим Ви, че използвахте нашето приложение и че подкрепяте бизнесите, които го използват.   <br />
    Поздрави,   <br />
    Екипът на {{ config('app.name') }}</p>
</x-mail::message>
