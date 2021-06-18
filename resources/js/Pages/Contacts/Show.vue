<template>
    <show-page>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Kontakte" :href="route('contacts')" />
                {{ contact.title }}
            </h2>
        </template>

        <template #info>
            <contact-card :contact="contact" />
        </template>

        <template #actions>
            <edit-button v-if="!contact.deleted_at" :href="route('contacts.edit', contact.id)" />
            <delete-button v-if="!contact.deleted_at" :href="route('contacts.destroy', contact.id)" />
            <restore-button v-if="contact.deleted_at" :href="route('contacts.restore', contact.id)" />
            <div v-if="contact.deleted_at">
                gelöscht: {{ contact.deleted_at }}
            </div>
        </template>

        <template #more>
            <div class="xl:col-span-6 col-span-12">
                <contract-table
                    :contracts="contact.buy_contracts"
                    :type="0"
                    :contactId="contact.id"
                    :show_upload="!contact.deleted_at"
                    :title="contact.buy_contracts.length > 1 ? contact.buy_contracts.length + ' Ankaufsverträge' : 'Ankaufsvertrag'"
                />
            </div>
            <div class="xl:col-span-6 col-span-12">
                <contract-table
                    :contracts="contact.sell_contracts"
                    :type="1"
                    :contactId="contact.id"
                    :show_upload="!contact.deleted_at"
                    :title="contact.sell_contracts.length > 1 ? contact.sell_contracts.length + ' Verkaufsverträge' : 'Verkaufsvertrag'"
                />
            </div>
        </template>
    </show-page>
</template>

<script>
import ShowPage from '@/Components/ShowPage.vue';
import BreadCrumb from '@/Components/BreadCrumb.vue';
import ContactCard from '@/Components/ContactCard.vue';
import BuyContractCard from '@/Components/BuyContractCard.vue';
import SellContractCard from '@/Components/SellContractCard.vue';
import EditButton from '@/Components/Buttons/EditButton.vue';
import DeleteButton from '@/Components/Buttons/DeleteButton.vue';
import RestoreButton from '@/Components/Buttons/RestoreButton.vue';
import ContractTable from '../../Components/Contracts/ContractTable.vue';

export default {
  components: {
    ShowPage,
    BreadCrumb,
    ContactCard,
    BuyContractCard,
    SellContractCard,
    EditButton,
    DeleteButton,
    RestoreButton,
    ContractTable,
  },

  props: {
    contact: Object,
  },
};
</script>
