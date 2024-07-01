<script setup>
import { Head, router, Link } from "@inertiajs/vue3";
import Layout from "../Layout.vue";
import Pagination from "@/Components/Pagination.vue";
import { defineProps } from "vue";

const props = defineProps({
    visits: Object,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses en JavaScript son 0-indexados
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
};
</script>

<template>
    <Layout>
        <Head title="Mis Visitas" />

        <div class="mx-20 space-y-4">
            <Link v-for="visit in props.visits.data" :key="visit.id" :href="route('placesclient.show', visit.place.id)" class="block p-4 bg-white shadow rounded-lg hover:bg-blue-100 transition duration-300">
                <h3 class="text-lg font-semibold">{{ visit.place.nombre }}</h3>
                <p class="text-gray-600">Fecha: {{ formatDate(visit.created_at) }}</p>
                <p class="text-gray-600">Lugar: {{ visit.place.subcategory.typecategory.category.nombre +'/'+visit.place.subcategory.typecategory.nombre +'/'+ visit.place.subcategory.nombre }}</p>
            </Link>
            <Pagination class="mt-2" :pagination="visits" />
        </div>
    </Layout>
</template>

