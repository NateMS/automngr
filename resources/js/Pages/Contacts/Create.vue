<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                Neuen Kontakt erfassen
            </h2>
        </template>

        <div>
            <contact-form :data="data" :meta="meta">
                <template #title>Neuen Kontakt erfassen</template>
                <template #description>Anschliessend können mit dem neuen Kontakt Verträge abgeschlossen werden.</template>
            </contact-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout.vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import ContactForm from './Components/ContactForm.vue';

export default {
  components: {
    Layout,
    BreadCrumb,
    ContactForm,
  },
  data() {
    return {
      meta: {
        form_name: 'CreateContact',
        route: this.route('contacts.store'),
        method: 'post',
        button_text: 'Kontakt speichern',
        on_success: 'Kontakt gespeichert',
      },
      data: {
        id: null,
        firstname: null,
        lastname: null,
        company: null,
        email: null,
        phone: null,
        address: null,
        zip: null,
        city: null,
        country: 'CH',
        notes: null,
      },
    };
  },
};
</script>
