<template>
    <jet-form-section @submitted="submitForm">
        <template #title>
            <slot name="title"></slot>
        </template>

        <template #description>
            <slot name="description"></slot>
        </template>

        <template #form>
            <div class="xl:col-span-2 md:col-span-3 sm:col-span-4 col-span-6 grid grid-cols-6 gap-3">
                <div class="col-span-6">
                    <jet-label for="date" value="Datum" />
                    <jet-input id="date" ref="date" type="text" class="mt-1 block w-full" v-model="form.date" />
                    <jet-input-error :message="form.errors.date" class="mt-2" />
                </div>
                <div class="col-span-6">
                    <jet-label for="delivery_date" value="Lieferdatum" />
                    <jet-input id="delivery_date" ref="delivery_date" type="text" class="mt-1 block w-full" v-model="form.delivery_date" />
                    <jet-input-error :message="form.errors.delivery_date" class="mt-2" />
                </div>

                <div v-if="data.type === '1'" class="col-span-6">
                    <jet-label for="insurance_type" value="Versicherung" />
                    <select v-model="form.insurance_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option v-for="(insurance, index) in insurance_types" :value="index"  v-bind:key="index" :selected="form.insurance_type == index">{{ insurance }}</option>
                    </select>
                    <jet-input-error :message="form.errors.insurance_type" class="mt-2" />
                </div>

                <div class="col-span-6">
                    <jet-label for="price" :value="form.type === '0' ? 'Einkaufspreis' : 'Verkaufspreis'" />
                    <currency-input v-model="form.price" :options="currencyOptions" id="price" class="w-full mt-1 block border-gray-300 rounded-md shadow-sm" ref="price"/>
                    <jet-input-error :message="form.errors.price" class="mt-2" />
                </div>

                <div v-if="!meta.is_edit" class="col-span-6">
                    <jet-label for="amount" value="Anzahlung" />
                    <currency-input v-model="form.amount" :options="currencyOptions" id="price" class="w-full mt-1 block border-gray-300 rounded-md shadow-sm" ref="amount"/>
                    <jet-input-error :message="form.errors.amount" class="mt-2" />
                </div>

                <div v-if="!meta.is_edit" class="col-span-6">
                    <jet-label for="payment_type" value="Einzahlungsart" />
                    <select v-model="form.payment_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="0" :selected="form.payment_type == '0'">Banküberweisung</option>
                        <option value="1" :selected="form.payment_type == '1'">Barzahlung</option>
                        <option value="2" :selected="form.payment_type == '2'">Cembra-Überweisung</option>
                    </select>
                    <jet-input-error :message="form.errors.type" class="mt-2" />
                </div>

                <div class="col-span-6">
                    <jet-label for="notes" value="Bemerkungen" />
                    <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" v-model="form.notes" ref="input">
                    </textarea>
                    <jet-input-error :message="form.errors.notes" class="mt-2" />
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                {{ meta.on_success }}
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing || ((!data.contact_id || !data.car_id) && !meta.is_edit)">
                {{ meta.button_text }}
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import { useForm } from '@inertiajs/vue3';
import CurrencyInput from '@/Components/CurrencyInput.vue';

export default {
  components: {
    JetButton,
    JetFormSection,
    JetLabel,
    JetInputError,
    JetActionMessage,
    JetInput,
    CurrencyInput,
  },
  props: {
    data: Object,
    meta: Object,
    insurance_types: Array,
  },
  data() {
    return {
      form: useForm(this.meta.form_name, this.data),
      currencyOptions: {
        currency: 'CHF',
        locale: 'de-CH',
        exportValueAsInteger: true,
        hideGroupingSeparatorOnFocus: false,
      },
    };
  },
  methods: {
    submitForm() {
      this.form.car_id = this.data.car_id;
      this.form.contact_id = this.data.contact_id;
      this.form.submit(this.meta.method, this.meta.route);
    },
  },
};
</script>
