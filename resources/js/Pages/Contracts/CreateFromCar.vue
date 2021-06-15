<template>
    <layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                <bread-crumb :text="car.name" :href="route('cars.show', car.id)" />
                Neuen {{ contractType }} erstellen
            </h2>
        </template>
        <div>
            <jet-form-section class="max-w-7xl" :emptyBg="true">
                <template #title>
                    Auto
                </template>
                <template #description>
                    Ausgewähltes Auto für diesen {{ contractType }}
                </template>
                <template #form>
                    <car-card class="col-span-12" :car="car" />
                </template>
            </jet-form-section>
            <jet-form-section class="max-w-7xl mt-5">
                <template #title>
                    {{ contactType }}
                </template>
                <template #description>
                    {{ contactType }} auswählen oder neu erfassen
                </template>
                <template #form>
                    <div class="col-span-3">
                        <jet-label for="contact" :value="contactType" />
                        <multiselect :disabled="createContact" v-model="contact" label="title" track-by="id" :options="contactsChoice" class="mt-1 block w-full" placeholder="Vertragspartner auswählen" />
                    </div>
                    <div class="col-span-6">
                        <contact-card v-if="contact.id" class="mt-3 col-span-4" :contact="contact" />
                    </div>
                    <div class="col-span-6">
                        oder
                    </div>
                    <div v-if="!createContact" class="col-span-6">
                        <button @click="openContactForm" class="bg-indigo-800 hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300 justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                            Neu erfassen
                        </button>
                    </div>
                    <div v-else class="col-span-6">
                        <p class="w-full mb-1 font-bold">Neuen Kontakt erfassen:</p>
                        <form @submit="submitCreateContactForm">
                            <div class="grid grid-cols-6 gap-6">
                                <contact-form-fields :form="contact" />
                                <div class="col-span-6 sm:col-span-4 flex items-center justify-end text-right">
                                    <jet-button>
                                        Kontakt speichern
                                    </jet-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </template>
                <template #actions>
                    <jet-button :disabled="!contact.id" @click="nextPage">
                        Nächster Schritt
                    </jet-button>
                </template>
            </jet-form-section>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import ContactFormFields from '@/Pages/Contacts/Components/ContactFormFields.vue';
import CarCard from '@/Components/CarCard.vue';
import ContactCard from '@/Components/ContactCard.vue';
import JetFormSection from '@/Jetstream/FormSection';
import Multiselect from 'vue-multiselect';
import JetLabel from '@/Jetstream/Label.vue';
import JetButton from '@/Jetstream/Button';
import JetActionMessage from '@/Jetstream/ActionMessage';

export default {
  components: {
    Layout,
    BreadCrumb,
    CarCard,
    ContactCard,
    JetFormSection,
    ContactFormFields,
    Multiselect,
    JetLabel,
    JetButton,
    JetActionMessage,
  },
  props: {
    car: Object,
    contacts: Object,
    type: String,
  },
  data() {
    return {
      contactsChoice: this.contacts,
      contact: {
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
        errors: {},
      },
      createContact: false,
    };
  },
  computed: {
    contractType() {
      return this.isSellContract ? 'Verkaufsvertrag' : 'Ankaufsvertrag';
    },
    contactType() {
      return this.isSellContract ? 'Käufer' : 'Verkäufer';
    },
    isSellContract() {
      return this.type == 'SellContract';
    },
    emptyContact() {
      return {
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
        errors: {},
      };
    },
  },
  methods: {
    nextPage() {
      this.$inertia.get(route('contracts.create', {
        type: this.isSellContract ? 1 : 0,
        car: this.car.id,
        contact: this.contact.id,
      }), { preserveScroll: true, carFirst: true });
    },
    openContactForm() {
      this.createContact = true;
      this.contact = this.emptyContact;
    },
    submitCreateContactForm(e) {
      e.preventDefault();
      axios.post(this.route('contacts.store_for_contract'), this.contact)
        .then((res) => {
          this.contactsChoice.push(res.data);
          this.contact = res.data;
          this.createContact = false;
        }).catch((err) => {
          if (err.response) {
            const { errors } = err.response.data;

            Object.keys(errors).map((key, index) => {
              errors[key] = errors[key].join(' ');
            });
            this.contact.errors = errors;
          }
        });
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
