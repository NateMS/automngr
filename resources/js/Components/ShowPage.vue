<template>
    <layout>
        <template #header>
            <slot name="header"></slot>
        </template>
        <div class="grid grid-cols-12 gap-12 w-full mb-10">
            <div class="col-span-6 xs:col-span-12">
                <slot name="info"></slot>
            </div>
            <div class="col-span-3 xs:col-span-12">
                <div class="w-full flex flex-col">
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-12 w-full">
            <slot name="more"></slot>
        </div>
    </layout>
</template>

<script>
import Layout from '@/Layouts/Layout'

export default {
    components: {
        Layout,
    },
}
</script>