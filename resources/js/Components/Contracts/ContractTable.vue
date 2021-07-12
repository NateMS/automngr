<template>
    <simple-table :title="title" :data="contracts" :columns="columns" :hideArrow="true">
        <template #actions>
            <create-contract-button v-if="show_upload" :type="type" :contactId="contactId" :carId="carId" />
        </template>
    </simple-table>
</template>

<script>
import CreateContractButton from '../Buttons/CreateContractButton.vue';
import SimpleTable from '@/Components/SimpleTable.vue';

export default {
    components: {
        CreateContractButton,
        SimpleTable,
    },
    props: {
        contracts: Array,
        contactId: Number,
        type: Number,
        carId: Number,
        show_upload: Boolean,
        title: String,
    },
    computed: {
        columns() {
            if (this.contracts.length === 0) {
                return [];
            }
            let columns = [{ key: 'date', value: 'Datum', sortable: false }];

            if (this.contracts[0].car) {
                columns.push({ key: 'car', value: 'Auto', sortable: false})
            }
            if (this.contracts[0].contact) {
                columns.push({ key: 'contact', value: this.type == '1' ? 'Käufer' : 'Verkäufer', sortable: false})
            }
            
            return columns;
        },
    },
};
</script>
