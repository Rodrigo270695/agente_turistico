<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import Layout from '../Layout.vue';
import Pagination from "@/Components/Pagination.vue";

defineProps({
    places: Array,
});
</script>

<template>
    <Layout>
        <Head title="Lugares" />

        <div class="relative flex justify-center items-center py-4 bg-cover bg-center" style="background-image: url('/path/to/background-image.jpg');">
            <div class="absolute inset-0 bg-[#e8a860] opacity-75"></div>
            <div class="relative z-10 text-center text-white">
                <h1 class="text-4xl font-bold mb-4">Encuentra la visita perfecta</h1>
                <div class="bg-white p-6 rounded-md shadow-md text-gray-900">
                    <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="origen" class="block text-sm font-medium">Provincia</label>
                            <input type="text" id="origen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#e8a860] focus:ring focus:ring-[#e8a860] focus:ring-opacity-50" placeholder="Ciudad de origen">
                        </div>
                        <div>
                            <label for="destino" class="block text-sm font-medium">Ciudad</label>
                            <input type="text" id="destino" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#e8a860] focus:ring focus:ring-[#e8a860] focus:ring-opacity-50" placeholder="Ciudad de destino">
                        </div>
                        <div>
                            <label for="categoria" class="block text-sm font-medium">Categoría</label>
                            <input type="text" id="categoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#e8a860] focus:ring focus:ring-[#e8a860] focus:ring-opacity-50" placeholder="Categoría">
                        </div>
                        <div>
                            <label for="tipo" class="block text-sm font-medium">Tipo</label>
                            <input type="text" id="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#e8a860] focus:ring focus:ring-[#e8a860] focus:ring-opacity-50" placeholder="Tipo">
                        </div>
                        <div class="col-span-1 md:col-span-2 lg:col-span-4">
                            <button type="submit" class="w-full bg-[#64161d] text-white py-2 rounded-md hover:bg-[#e8a860]">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tourist Centers Section -->
        <div class="mx-4 md:mx-28 py-12">
            <h2 class="text-2xl font-bold text-center mb-8 text-[#64161d]">Centros Turísticos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="place in places.data" :key="place.id" class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img :src="place.photos && place.photos.length ? place.photos[0].url : '/path/to/default-image.jpg'" :alt="place.nombre" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-[#64161d] mb-2">{{ place.nombre }}</h3>
                        <p class="text-gray-600 mb-2"><strong>Provincia/Distrito:</strong> {{ place.district.province.name }} / {{ place.district.name }}</p>
                        <p class="text-gray-600 mb-2"><strong>Época de visita:</strong> {{ place.epoca_visita }}</p>
                        <p class="text-gray-600 mb-2"><strong>Dirección:</strong> {{ place.direccion }}</p>
                        <p class="text-gray-600 mb-4"><strong>Días abierto:</strong> {{ place.dias_abierto_desde }} - {{ place.dias_cerrado_hasta }}</p>
                        <Link :href="route('placesclient.show', place.id)" class="inline-block bg-[#64161d] text-white py-2 px-4 rounded-md hover:bg-[#e8a860]">Ver Detalles</Link>
                    </div>
                </div>
            </div>
            <Pagination class="mt-6" :pagination="places" />
        </div>
    </Layout>
</template>

<style>
/* Estilos adicionales para mejorar la apariencia */
body {
    font-family: 'Inter', sans-serif;
}

input, select, button {
    transition: all 0.3s ease;
}

input:focus, select:focus, button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(232, 168, 96, 0.5);
}

button:hover {
    background-color: #e8a860;
}
</style>
