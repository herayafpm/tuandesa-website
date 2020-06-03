@component('mail::message')

{{$user->name}} silahkan klik tombol dibawah untuk mengubah password anda

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
