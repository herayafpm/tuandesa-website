@component('mail::message')

Terima kasih {{$user->name}} telah menggunakan aplikasi {{config('app.name')}}
silahkan klik tombol dibawah untuk dapat menggunakan aplikasi 

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
