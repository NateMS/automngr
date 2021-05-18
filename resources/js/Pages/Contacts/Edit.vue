<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                {{ title }}
            </h2>
        </template>
   
        <div>
            <div class="max-w-7xl py-10 sm:px-6 lg:px-8">
                <contact-form :form="form" :meta="meta">
                    <template #title>Kontaktinformationen</template>
                    <template #description>
                        Kontaktinformationen anschauen &amp; anpassen.
                        <contact-card :contact="computedContact" />
                    </template>
                </contact-form>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <simple-table :title="'An ' + title + ' verkaufte Autos'" :data="contact.bought_cars" :columns="boughtCarColumns" />
            </div>
            <div class="max-w-7xl pt-6 sm:px-6 lg:px-8">    
                <simple-table :title="'Von ' + title + ' gekaufte Autos'" :data="contact.sold_cars" :columns="soldCarColumns" />
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'
import BreadCrumb from '@/Components/BreadCrumb.vue'
import ContactCard from '@/Components/ContactCard.vue'
import SimpleTable from '@/Components/SimpleTable.vue'
import ContactForm from './Components/ContactForm.vue'

export default {
    components: {
        Layout,
        BreadCrumb,
        SimpleTable,
        ContactForm,
        ContactCard,
    },

    props: {
        contact: Object,
    },
    data() {
        return {
            meta: {
                link: 'contacts.update',
                button_text: 'Änderungen speichern',
                on_success: 'Änderungen gespeichert',
            },
            form: this.$inertia.form({
                _method: 'PUT',
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
            }),
            boughtCarColumns: [
                {key: 'name', value: 'Auto'},
                {key: 'date', value: 'Verkaufsdatum'},
                {key: 'price', value: 'Verkaufspreis'},
                {key: 'insurance_type', value: 'Versicherungstyp'},
            ],
            soldCarColumns: [
                {key: 'name', value: 'Auto'},
                {key: 'date', value: 'Kaufdatum'},
                {key: 'price', value: 'Kaufpreis'},
            ]
        }
    },
    computed: {
        title: function () {
            if (this.form.company) {
                return this.form.company;
            }

            return this.form.lastname + ' ' + this.form.firstname;
        }, 
        computedContact: function () {
            return {
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                company: this.form.company,
                email: this.form.email,
                phone: this.form.phone,
                address: this.form.address,
                zip: this.form.zip,
                city: this.form.city,
                country: this.form.country,
            }
        }
    },
}
</script>