<template>
    <dialog-modal :show="showModal" @close="$emit('close')">
        <template #title>
            Neue Einzahlung
        </template>
        <template #content>
            <form @submitted="submitForm">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="date" value="Datum" />
                        <datepicker id="date" ref="date" v-model="form.date" inputFormat="dd.MM.yyyy" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" />
                        <jet-input-error :message="form.errors.date" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="amount" value="Betrag" />
                        <jet-input id="amount" type="text" class="mt-1 block w-full" v-model="form.amount" ref="amount" autocomplete="amount" />
                        <jet-input-error :message="form.errors.amount" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="type" value="Einzahlungsart" />
                        <select v-model="form.type" class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option value="0" :selected="form.type == '0'">Banküberweisung</option>
                            <option value="1" :selected="form.type == '1'">Barzahlung</option>
                        </select>
                        <jet-input-error :message="form.errors.type" class="mt-2" />
                    </div>
                </div>
            </form>
        </template>
        <template #footer>
            <jet-button @click="submitForm" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Einzahlung speichern
            </jet-button>
        </template>
    </dialog-modal>
    <!-- <multiselect class="mt-1 block w-full" @select="updateTypeSelection" v-model="typeSelection" deselect-label="Kann nicht entfernt werden" track-by="key" label="label" placeholder="Versicherung auswählen" :options="types" :searchable="false" :allow-empty="false" /> -->
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetLabel from '@/Jetstream/Label.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError'
import Datepicker from 'vue3-datepicker'
import { useForm } from '@inertiajs/inertia-vue3'
import { ref } from 'vue'
import DialogModal from '@/Jetstream/DialogModal.vue'

export default {
    components: {
        JetButton,
        JetLabel,
        JetInput,
        JetInputError,
        DialogModal,
        Datepicker,
    },
    props: {
        id: Number,
        showModal: Boolean,
    },
    data() {
        return {
            form: useForm('CreatePayment', {
                id: null,
                date: ref(new Date()),
                amount: null,
                type: '1',
                contract_id: this.id,
            }),
        }
    },
    methods: {
        submitForm() {
            this.form.post(this.route('payments.store', this.id), {
                preserveScroll: true,
                onSuccess: () => {
                    this.$emit('close');
                    form.reset();
                },
            });
        },
    },
}
</script>