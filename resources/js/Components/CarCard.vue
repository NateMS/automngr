<template>
    <div class="p-5 bg-white shadow rounded-md font-medium">
        <div v-if="car.name" class="font-bold pb-1 mb-1 text-2xl border-b">
            {{ car.name }}
        </div>
        <div class="grid grid-cols-4 gap-2 w-full">
            <div class="col-span-1 xs:col-span-2">
                Stammnummer
            </div>
            <div v-if="car.stammnummer" class="col-span-3 xs:col-span-2">
                {{ car.stammnummer ? car.stammnummer : '-' }}
            </div>
            <div v-if="car.vin" class="col-span-1 xs:col-span-2">
                Chassisnummer
            </div>
            <div v-if="car.vin" class="col-span-3 xs:col-span-2">
                {{ car.vin ? car.vin : '-'}}
            </div>
            <div class="col-span-1 xs:col-span-2">
                Farbe
            </div>
            <div class="col-span-1 xs:col-span-2">
                {{ car.colour ? car.colour : '-' }}
            </div>
            <div class="col-span-1 xs:col-span-2">
                Kilometerstand
            </div>
            <div class="col-span-1 xs:col-span-2">
                {{ car.kilometers ? car.kilometers + ' KM' : '-' }}
            </div>

            <div class="col-span-1 xs:col-span-2">
                Erstzulassung
            </div>
            <div class="col-span-1 xs:col-span-2">
                {{ car.initial_date ? car.initial_date  : '-' }}
            </div>

            <div class="col-span-1 xs:col-span-2">
                Letzte Prüfung
            </div>
            <div class="col-span-1 xs:col-span-2">
                {{ car.last_check_date ? car.last_check_date  : '-' }}
            </div>
        </div>
        <div v-if="car.known_damage" class="mt-3">
            <p class="font-bold">Bekannter Schaden</p>
            {{ car.known_damage }}
        </div>
        <div v-if="car.notes" class="mt-3">
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
