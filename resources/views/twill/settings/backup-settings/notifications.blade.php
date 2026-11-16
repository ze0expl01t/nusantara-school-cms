@twillBlockTitle('Notifications')
@twillBlockIcon('mail')
@twillBlockGroup('app')

<x-twill::input
    name="email_to"
    label="Email Notifikasi"
    default="admin@smkmuh1blora.sch.id"
    placeholder="email@perusahaan.sch.id"
    note="Email tujuan notifikasi jika backup gagal atau unhealthy."
/>

<x-twill::checkbox
    name="notify_on_failure"
    label="Kirim notifikasi jika backup GAGAL"
    default="true"
/>

<x-twill::checkbox
    name="notify_on_success"
    label="Kirim notifikasi jika backup SUKSES"
    default="false"
    note="Disarankan nonaktif agar tidak spam email."
/>

<x-twill::checkbox
    name="notify_on_unhealthy"
    label="Kirim notifikasi jika backup UNHEALTHY"
    default="true"
/>
