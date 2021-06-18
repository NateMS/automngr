<template>
    <show-page>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <bread-crumb text="Autos" :href="route('cars')" />
                {{ car.name }}
            </h2>
        </template>
        <template #info>
            <car-card :car="car" />
        </template>
        <template #actions>
            <edit-button v-if="!car.deleted_at" :href="route('cars.edit', car.id)" />
            <delete-button v-if="!car.deleted_at" :href="route('cars.destroy', car.id)" />
            <restore-button v-if="car.deleted_at" :href="route('cars.restore', car.id)" />
            <div v-if="car.deleted_at">
                gelöscht: {{ car.deleted_at }}
            </div>
        </template>
        <template #more>
            <div class="xl:col-span-6 col-span-12">
                <simple-table :title="car.buy_contracts.total > 1 ? car.buy_contracts.total + ' Ankaufsverträge' : 'Ankaufsvertrag'" :data="car.buy_contracts" :columns="buyContractColumns" :currentRoute="currentRoute" :hideArrow="true">
                    <template #actions>
                        <standard-button v-if="!car.deleted_at" colour="green" :href="route('contracts.create_from_car', [0, car.id])">
                        <unicon fill="currentColor" class="mr-1" height="22" width="22" name="plus-circle"></unicon>
                        Ankaufsvertrag
                    </standard-button>
                    </template>
                </simple-table>
            </div>
            <div class="xl:col-span-6 col-span-12">
                <simple-table :title="car.sell_contracts.total > 1 ? car.sell_contracts.total + ' Verkaufsverträge' : 'Verkaufsvertrag'" :data="car.sell_contracts" :columns="sellContractColumns" :currentRoute="currentRoute" :hideArrow="true">
                    <template #actions>
                        <standard-button v-if="!car.deleted_at" colour="green" :href="route('contracts.create_from_car', [1, car.id])">
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
import CarCard from '@/Components/CarCard.vue';
import BuyContractCard from '@/Components/BuyContractCard.vue';
import SellContractCard from '@/Components/SellContractCard.vue';
import EditButton from '@/Components/Buttons/EditButton.vue';
import DeleteButton from '@/Components/Buttons/DeleteButton.vue';
import RestoreButton from '@/Components/Buttons/RestoreButton.vue';
import StandardButton from '@/Components/Buttons/StandardButton.vue';
import SimpleTable from '@/Components/SimpleTable.vue';

export default {
  components: {
    ShowPage,
    BreadCrumb,
    CarCard,
    BuyContractCard,
    SellContractCard,
    EditButton,
    DeleteButton,
    RestoreButton,
    StandardButton,
    SimpleTable,
  },
  props: {
    car: Object,
  },
  data() {
    return {
      currentRoute: 'cars.show',
      buyContractColumns: [
        { key: 'date', value: 'Datum', sortable: false },
        { key: 'contact', value: 'Verkäufer', sortable: false },
        { key: 'price', value: 'Einkaufspreis', sortable: false },
      ],
      sellContractColumns: [
        { key: 'date', value: 'Datum', sortable: false },
        { key: 'contact', value: 'Käufer', sortable: false },
        { key: 'price', value: 'Verkaufspreis', sortable: false },
      ],
    };
  },
};
</script>
