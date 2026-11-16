@twillBlockTitle('Schedule')
@twillBlockIcon('clock')
@twillBlockGroup('app')

<x-twill::input
    name="backup_time"
    label="Jam Backup"
    default="02:00"
    placeholder="HH:MM"
    note="Format 24 jam, contoh: 02:00 untuk jam 2 pagi WIB."
/>

<x-twill::input
    name="cleanup_time"
    label="Jam Cleanup"
    default="01:30"
    placeholder="HH:MM"
    note="Jam untuk membersihkan backup lama."
/>

<x-twill::input
    name="monitor_time"
    label="Jam Monitor"
    default="09:00"
    placeholder="HH:MM"
    note="Jam untuk cek kesehatan backup."
/>

<x-twill::select
    name="timezone"
    label="Timezone"
    default="Asia/Jakarta"
    :options="[
        ['value' => 'Asia/Jakarta', 'label' => 'WIB (Asia/Jakarta)'],
        ['value' => 'Asia/Makassar', 'label' => 'WITA (Asia/Makassar)'],
        ['value' => 'Asia/Jayapura', 'label' => 'WIT (Asia/Jayapura)'],
    ]"
/>
