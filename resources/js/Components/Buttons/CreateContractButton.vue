<template>
    <standard-button colour="green" :href="link">
        <unicon fill="currentColor" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
        {{ text }}
    </standard-button>
</template>

<script>
import StandardButton from './StandardButton.vue';

export default {
    components: {
      StandardButton,
    },
    props: {
        type: Number,
        contactId: Number,
        carId: Number
    },
    computed: {
        text() {
            return this.type === 1 ? 'Verkaufsvertrag' : 'Ankaufsvertrag';
        },
        link() {
            if (this.contactId && this.carId) {
                return route('contracts.create', [this.type, this.carId, this.contactId]);
            }
            if (this.contactId) {
                return route('contracts.create_from_contact', [this.type, this.contactId]);
            }
            return route('contracts.create_from_car', [this.type, this.carId]);
        },
    },
};
</script>
