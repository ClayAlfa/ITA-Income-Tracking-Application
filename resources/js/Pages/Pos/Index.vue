<script setup>
import PosHeader from "../../Components/Pos/PosHeader.vue";
import PosStats from "../../Components/Pos/PosStats.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="max-w-6xl mx-auto p-4">
        <Head title="POS Dashboard" />

        <PosHeader />

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <PosStats />

            <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-2">Quick Actions</h3>
                <div class="flex gap-2">
                    <Link
                        :href="route('pos.dashboard')"
                        class="px-4 py-2 bg-blue-600 text-white rounded"
                        >New Sale</Link
                    >
                    <Link
                        :href="route('pos.open')"
                        class="px-4 py-2 bg-gray-600 text-white rounded"
                        >Open Session</Link
                    >
                    <Link
                        :href="route('pos.close')"
                        class="px-4 py-2 bg-green-600 text-white rounded"
                        >Close Session</Link
                    >
                </div>

                <div class="mt-6 text-sm text-gray-500">
                    This is a starter POS dashboard. Replace placeholders with
                    live data as needed.
                </div>
            </div>
        </div>
    </div>
</template>
