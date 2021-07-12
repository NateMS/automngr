<template>
    <jet-form-section :emptyBg="existing_contact.id !== null">
      <template #title>
          {{ contactType }}
      </template>
      <template #description>
          {{ contactType }} auswählen oder neu erfassen
      </template>
      <template v-if="existing_contact.id" #form>
          <contact-card v-if="existing_contact.id" class="xl:col-span-3 md:col-span-4 col-span-6" :contact="contact" />
      </template>
      <template v-else #form>
          <div class="col-span-6">
              <jet-label for="contact" :value="contactType + ' auswählen'" />
              <div class="grid grid-cols-12 gap-3 gap-y-6 mt-1">
                <multiselect :allow-empty="false" @select="onContactChange" :disabled="createContact" v-model="contact" label="title" track-by="id" :options="contactsChoice" class="sm:col-span-6 col-span-12" :placeholder="contactType + 'wählen'" />
                <div v-if="!createContact" class="sm:col-span-6 col-span-12">
                  <span class="mr-2">oder</span>
                  <button @click="openContactForm" class="bg-indigo-800 hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300 justify-center inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring disabled:opacity-25 transition">
                      Neu erfassen
                  </button>
                </div>
            </div>
          </div>
          <div v-if="contact.id" class="xl:col-span-3 md:col-span-4 col-span-6">
              <contact-card :contact="contact" hideEmpty="true" />
          </div>
          <div v-if="createContact" class="col-span-6">
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
  </jet-form-section>
</template>

<script>
import ContactFormFields from '@/Pages/Contacts/Components/ContactFormFields.vue';
import ContactCard from '@/Components/ContactCard.vue';
import JetFormSection from '@/Jetstream/FormSection';
import Multiselect from 'vue-multiselect';
import JetLabel from '@/Jetstream/Label.vue';
import JetButton from '@/Jetstream/Button';

export default {
  components: {
    ContactCard,
    JetFormSection,
    ContactFormFields,
    Multiselect,
    JetLabel,
    JetButton,
  },
  props: {
    existing_contact: Object,
    contacts: Object,
    type: String,
  },
  data() {
    return {
      contactsChoice: this.contacts,
      createContact: false,
      contact: {
        id: this.existing_contact?.id ?? null,
        firstname: this.existing_contact?.firstname ?? null,
        name: this.existing_contact?.name ?? null,
        title: this.existing_contact?.title ?? null,
        lastname: this.existing_contact?.lastname ?? null,
        company: this.existing_contact?.company ?? null,
        email: this.existing_contact?.email ?? null,
        phone: this.existing_contact?.phone ?? null,
        address: this.existing_contact?.address ?? null,
        zip: this.existing_contact?.zip ?? null,
        city: this.existing_contact?.city ?? null,
        country: this.existing_contact?.country ?? 'CH',
        notes: this.existing_contact?.notes ?? null,
        errors: {},
      },
    };
  },
  computed: {
    contactType() {
      return this.isSellContract ? 'Käufer' : 'Verkäufer';
    },
    isSellContract() {
      return this.type === '1';
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
        country: 'CH',
        notes: null,
        errors: {},
      };
    },
  },
  methods: {
    openContactForm() {
      this.createContact = true;
      this.contact = this.emptyContact;
    },
    onContactChange(contact, val) {
        this.triggerChange(contact.id);
    },
    triggerChange(val) {
        this.$emit('contact-id-change', val);
    },
    submitCreateContactForm(e) {
      e.preventDefault();
      axios.post(this.route('contacts.store_for_contract'), this.contact)
        .then((res) => {
          this.contactsChoice.push(res.data);
          this.contact = res.data;
          this.createContact = false;
          this.triggerChange(this.contact.id);
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