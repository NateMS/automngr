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
                <contract-table
                    :contracts="car.buy_contracts"
                    :type="0"
                    :carId="car.id"
                    :show_upload="!car.deleted_at"
                    :title="car.buy_contracts.length > 1 ? car.buy_contracts.length + ' Ankaufsverträge' : 'Ankaufsvertrag'"
                />
            </div>
            <div class="xl:col-span-6 col-span-12">
                <contract-table
                    :contracts="car.sell_contracts"
                    :type="1"
                    :carId="car.id"
                    :show_upload="!car.deleted_at"
                    :title="car.sell_contracts.length > 1 ? car.sell_contracts.length + ' Verkaufsverträge' : 'Verkaufsvertrag'"
                />
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
import ContractTable from '../../Components/Contracts/ContractTable.vue';

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
    ContractTable,
  },
  props: {
    car: Object,
  },
};
</script>
