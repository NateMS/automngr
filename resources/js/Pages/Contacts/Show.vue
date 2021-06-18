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
                <simple-table :title="contact.buy_contracts.total > 1 ? contact.buy_contracts.total + ' Ankaufsverträge' : 'Ankaufsvertrag'" :data="contact.buy_contracts" :columns="buyContractColumns" :currentRoute="currentRoute" :hideArrow="true">
                    <template #actions>
                        <standard-button v-if="!contact.deleted_at" colour="green" :href="route('contracts.create_from_contact', [0, contact.id])">
                        <unicon fill="currentColor" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
                        Ankaufsvertrag
                    </standard-button>
                    </template>
                </simple-table>
            </div>
            <div class="xl:col-span-6 col-span-12">
                <simple-table :title="contact.sell_contracts.total > 1 ? contact.sell_contracts.total + ' Verkaufsverträge' : 'Verkaufsvertrag'" :data="contact.sell_contracts" :columns="sellContractColumns" :currentRoute="currentRoute" :hideArrow="true">
                    <template #actions>
                        <standard-button v-if="!contact.deleted_at" colour="green" :href="route('contracts.create_from_contact', [1, contact.id])">
                            <unicon fill="currentColor" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
                            Verkaufssvertrag
                        </standard-button>
                    </template>
                </simple-table>
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
import SimpleTable from '@/Components/SimpleTable.vue';
import StandardButton from '@/Components/Buttons/StandardButton.vue';

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
    SimpleTable,
    StandardButton,
  },

  props: {
    contact: Object,
  },
  data() {
    return {
      currentRoute: 'contacts.show',
      buyContractColumns: [
        { key: 'date', value: 'Datum', sortable: false },
        { key: 'car', value: 'Auto', sortable: false},
        { key: 'price', value: 'Einkaufspreis', sortable: false },
      ],
      sellContractColumns: [
        { key: 'date', value: 'Datum', sortable: false },
        { key: 'car', value: 'Auto', sortable: false},
        { key: 'price', value: 'Verkaufspreis', sortable: false },
      ],
    };
  },
};
</script>
