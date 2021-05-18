<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                Neuen Kontakt erfassen
            </h2>
        </template>
   
        <div>
            <contact-form :form="form" :meta="meta">
                <template #title>Neuen Kontakt erfassen</template>
                <template #description>Anschliessend können mit dem neuen Kontakt Verträge abgeschlossen werden.</template>
            </contact-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import ContactForm from './Components/ContactForm.vue'

export default {
    components: {
        Layout,
        BreadCrumb,
        ContactForm,
    },
    data() {
        return {
            meta: {
                link: 'contacts.store',
                button_text: 'Kontakt speichern',
                on_success: 'Kontakt gespeichert',
            },
            form: this.$inertia.form({
                _method: 'POST',
                id: null,
                firstname: null,
                lastname: null,
                company: null,
                email: null,
                phone: null,
                address: null,
                zip: null,
                city: null,
                country: null,
                notes: null,
            }),
        }
    },
}
</script>