<template>
    <div class="max-w-7xl">
        <jet-form-section @submitted="submitForm">
            <template #title>
                <slot name="title"></slot>
            </template>

            <template #description>
                <slot name="description"></slot>
            </template>

            <template #form>
                <car-form-fields :form="form" :brands="brands" :brand="brand" :car_model="car_model" />
            </template>

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    {{ meta.on_success }}
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ meta.button_text }}
                </jet-button>
            </template>
        </jet-form-section>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import CarFormFields from '@/Pages/Cars/Components/CarFormFields.vue';
import { useForm } from '@inertiajs/vue3';

export default {
  components: {
    JetButton,
    JetFormSection,
    JetActionMessage,
    CarFormFields,
  },
  props: {
    data: Object,
    brands: Array,
    meta: Object,
    brand: Object,
    car_model: Object,
  },
  data() {
    return {
      form: useForm(this.meta.form_name, this.data),
    };
  },
  methods: {
    submitForm() {
      this.form.submit(this.meta.method, this.meta.route);
    },
  },
};
</script>
