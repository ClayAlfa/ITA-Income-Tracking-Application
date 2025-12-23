<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    status: { type: String, default: "Closed" },
});
</script>

<template>
    <div
        class="bg-white p-6 rounded-lg shadow flex items-center justify-between"
    >
        <div>
            <h1 class="text-2xl font-bold">Point of Sale</h1>
            <p class="text-sm text-gray-500">
                Status: <span class="font-medium">{{ status }}</span>
            </p>
        </div>

        <div class="flex gap-2">
            <Link
                :href="route('pos.open')"
                class="px-4 py-2 bg-blue-600 text-white rounded"
                >Open Session</Link
            >
            <Link
                :href="route('pos.close')"
                class="px-4 py-2 bg-green-600 text-white rounded"
                >Close Session</Link
            >
        </div>
    </div>
</template>
