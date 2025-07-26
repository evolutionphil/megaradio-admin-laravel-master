@component('mail::message')

Your station added successfully!

@component('mail::button', ['url' => $url])
Click here to listen.
@endcomponent

Thanks,<br>
MegaRadio
@endcomponent
