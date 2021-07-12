<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb v-if="!car.id && !contact.id" text="Verträge" :href="route('dashboard')" />
                <bread-crumb v-if="car.id && !contact.id" text="Autos" :href="route('cars')" />
                <bread-crumb v-if="car.id && !contact.id" :text="car.name" :href="route('cars.show', car.id)" />
                <bread-crumb v-if="!car.id && contact.id" text="Kontakte" :href="route('contacts')" />
                <bread-crumb v-if="!car.id && contact.id" :text="contact.name" :href="route('contacts.show', contact.id)" />
                Neuen {{ contractType }} erstellen
            </h2>
        </template>

        <div>
            <contact-create-or-select v-if="contact.id" class="mb-5" @contact-id-change="updateContactId" :existing_contact="contact" :contacts="contacts" :type="data.type" />
            <car-create-or-select @car-id-change="updateCarId" :existing_car="car" :cars="cars" :brands="brands" :type="data.type" />
            <contact-create-or-select v-if="!contact.id" class="mt-5" @contact-id-change="updateContactId" :existing_contact="contact" :contacts="contacts" :type="data.type" />
            <contract-form class="mt-5" :data="data" :meta="meta" :insurance_types="insurance_types">
                <template #title>Vertragsinformationen erfassen</template>
                <template #description>Der Vertrag kann anschliessend gespeichert werden.</template>
            </contract-form>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import ContactCreateOrSelect from '@/Components/Contacts/CreateOrSelect.vue';
import CarCreateOrSelect from '@/Components/Cars/CreateOrSelect.vue';
import ContractForm from './Components/ContractForm.vue';

export default {
  components: {
    Layout,
    BreadCrumb,
    ContractForm,
    ContactCreateOrSelect,
    CarCreateOrSelect,
  },
  props: {
    car: Object,
    contact: Object,
    type: String,
    insurance_types: Array,
    contacts: Array,
    cars: Array,
    brands: Array,
  },
  data() {
    return {
      meta: {
        form_name: 'CreateContract',
        route: this.route('contracts.store'),
        method: 'post',
        button_text: 'Vertrag speichern',
        on_success: 'Vertrag gespeichert',
      },
      data: {
        id: null,
        date: new Date().toJSON().slice(0,10).split('-').reverse().join('.'),
        delivery_date: new Date().toJSON().slice(0,10).split('-').reverse().join('.'),
        price: null,
        notes: null,
        type: this.type,
        insurance_type: '0',
        car_id: this.car?.id ?? null,
        contact_id: this.contact?.id ?? null,
        amount: null,
        payment_type: '1',
      },
    };
  },
  computed: {
    contractType() {
      return this.data.type === '1' ? 'Verkaufsvertrag' : 'Ankaufsvertrag';
    },
  },
  methods: {
    updateContactId(val) {
      this.data.contact_id = parseInt(val);
    },
    updateCarId(val) {
      this.data.car_id = parseInt(val);
    },
  },
};
</script>