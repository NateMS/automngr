<template>
    <div class="p-5 bg-white shadow rounded-md font-medium">
        <div v-if="car.name" class="font-bold pb-1 mb-1 text-2xl border-b">
            {{ car.name }}
        </div>
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-2 w-full">
            <div v-if="!hideEmpty || car.stammnummer" class="col-span-1">
                Stammnummer
            </div>
            <div v-if="!hideEmpty || car.stammnummer" class="col-span-1 xl:col-span-3">
                {{ car.stammnummer ? car.stammnummer : '-' }}
            </div>
            <div v-if="!hideEmpty || car.vin" class="col-span-1">
                Chassisnummer
            </div>
            <div v-if="!hideEmpty || car.vin" class="col-span-1 xl:col-span-3">
                {{ car.vin ? car.vin : '-'}}
            </div>
            <div v-if="!hideEmpty || car.colour" class="col-span-1">
                Farbe
            </div>
            <div v-if="!hideEmpty || car.colour" class="col-span-1">
                {{ car.colour ? car.colour : '-' }}
            </div>
            <div v-if="!hideEmpty || car.kilometers" class="col-span-1">
                Kilometerstand
            </div>
            <div v-if="!hideEmpty || car.kilometers" class="col-span-1">
                {{ car.kilometers ? car.kilometers + ' KM' : '-' }}
            </div>
            <div v-if="!hideEmpty || car.initial_date" class="col-span-1">
                Erstzulassung
            </div>
            <div v-if="!hideEmpty || car.initial_date" class="col-span-1">
                {{ car.initial_date ? car.initial_date  : '-' }}
            </div>
            <div v-if="!hideEmpty || car.last_check_date" class="col-span-1">
                Letzte Prüfung
            </div>
            <div v-if="!hideEmpty || car.last_check_date" class="col-span-1">
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
            <inertia-link :href="car.link" class="pt-1 pb-1 flex items-center hover:text-indigo-600">
                <unicon fill="currentColor" class="mr-1" height="22" width="22" name="arrow-right"></unicon>
                Zum Auto
            </inertia-link>
        </div>
    </div>
</template>

<script>

export default ({
  props: {
    car: Object,
    hideEmpty: String,
  },
});
</script>
