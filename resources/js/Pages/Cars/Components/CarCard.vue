<template>
    <div class="p-5 bg-white shadow rounded-md font-medium">
        <div v-if="car.name" class="font-bold py-1 flex align-items">
            {{ car.name }}
        </div>
        <div v-if="car.colour" class="py-1 flex align-items">
            <unicon class="mr-1" height="22" width="22" name="palette"></unicon>
            {{ car.colour }}
        </div>
        <div v-if="car.stammnummer" class="py-1 flex align-items">
            St-Nr: {{ car.stammnummer }}
        </div>
        <div v-if="car.vin" class="py-1 flex align-items">
            VIN: {{ car.vin }}
        </div>
        <div v-if="car.kilometers" class="py-1 flex align-items">
            {{ car.kilometers }} KM
        </div>
        <div v-if="car.initial_date" class="py-1 flex align-items">
            <unicon class="mr-1" height="22" width="22" name="calendar-alt"></unicon>
            {{ car.initial_date }}
        </div>
        <div v-if="car.last_check_date" class="py-1 flex align-items">
            Letzte Prüfung: {{ car.last_check_date }}
        </div>
        <div v-if="car.known_damage" class="py-3">
            <p class="font-bold">Bekannter Schaden</p>
            {{ car.known_damage }}
        </div>
        <div v-if="car.notes" class="py-3">
            <p class="font-bold">Notizen</p>
            {{ car.notes }}
        </div>
        <div v-if="car.link" class="pt-3 mt-3 border-t">
            <inertia-link :href="car.link" class="pt-1 pb-1 flex items-center">
                <unicon class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                Zum Auto
            </inertia-link>
        </div>
    </div>
</template>

<script>

export default ({
    props: {
        car: Object,
    },
})
</script>
