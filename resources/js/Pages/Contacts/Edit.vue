<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                <bread-crumb :text="title" :href="route('contacts.show', contact.id)" />
                bearbeiten
            </h2>
        </template>
   
        <div>
            <contact-form :data="data" :meta="meta">
                <template #title>Kontaktinformationen</template>
                <template #description>
                    Kontaktinformationen anschauen &amp; anpassen.
                </template>
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

    props: {
        contact: Object,
    },
    data() {
        return {
            meta: {
                form_name: 'EditContact' + this.contact.id,
                route: this.route('contacts.update', this.contact.id),
                method: 'put',
                button_text: 'Änderungen speichern',
                on_success: 'Änderungen gespeichert',
            },
            data: {
                id: this.contact.id,
                firstname: this.contact.firstname,
                lastname: this.contact.lastname,
                company: this.contact.company,
                email: this.contact.email,
                phone: this.contact.phone,
                address: this.contact.address,
                zip: this.contact.zip,
                city: this.contact.city,
                country: this.contact.country,
                notes: this.contact.notes,
            },
        }
    },
    computed: {
        title: function () {
            if (this.data.company) {
                return this.data.company;
            }

            return this.data.lastname + ' ' + this.data.firstname;
        }, 
    },
}
</script>