<script setup>
const stats = [
    { label: "Total Sales", value: "Rp0" },
    { label: "Total Profit", value: "Rp0" },
    { label: "Start Cash", value: "Rp0" },
    { label: "Expected Cash", value: "Rp0" },
];
</script>

<template>
    <div class="bg-white p-6 rounded-lg shadow grid grid-cols-2 gap-4">
        <div v-for="(s, i) in stats" :key="i" class="p-3 border rounded">
            <div class="text-sm text-gray-500">{{ s.label }}</div>
            <div class="text-xl font-semibold">{{ s.value }}</div>
        </div>
    </div>
</template>
