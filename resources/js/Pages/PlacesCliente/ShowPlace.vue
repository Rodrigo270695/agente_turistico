<script setup>
import { defineProps, defineEmits } from 'vue';
import Layout from '../Layout.vue';
import MapComponent from '@/Components/MapComponent.vue';
import { useForm, Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    place: Object,
    recommendedPlaces: Array,
});

const emit = defineEmits(['close-modal']);

const form = useForm({
    visit: props.place.id,
});

const visitPlace = () => {
    form.post(route("visit.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
}
</script>

<template>
    <Layout>
        <Head :title="props.place.nombre" />

        <div class="mx-4 md:mx-28 py-12">
            <h1 class="text-4xl font-bold text-center mb-8 text-[#64161d]">{{ props.place.nombre }}</h1>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="relative">
                    <div class="carousel">
                        <div v-for="(photo, index) in props.place.photos" :key="index" class="carousel-item">
                            <img :src="photo.url" :alt="props.place.nombre" class="w-full h-64 object-cover">
                        </div>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 mb-2"><strong>Provincia/Distrito:</strong> {{ props.place.district.province.name }} / {{ props.place.district.name }}</p>
                        <p class="text-gray-600 mb-2"><strong>Época de visita:</strong> {{ props.place.epoca_visita }}</p>
                        <p class="text-gray-600 mb-2"><strong>Dirección:</strong> {{ props.place.direccion }}</p>
                        <p class="text-gray-600 mb-2"><strong>Días abierto:</strong> {{ props.place.dias_abierto_desde }} - {{ props.place.dias_cerrado_hasta }}</p>
                        <p class="text-gray-600 mb-2"><strong>Hora de apertura:</strong> {{ props.place.hora_apertura }}</p>
                        <p class="text-gray-600 mb-2"><strong>Hora de cierre:</strong> {{ props.place.hora_cierre }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 mb-2"><strong>Latitud:</strong> {{ props.place.latitud }}</p>
                        <p class="text-gray-600 mb-2"><strong>Longitud:</strong> {{ props.place.longitud }}</p>
                        <p class="text-gray-600 mb-2"><strong>Distancia (horas):</strong> {{ props.place.distancia_horas }}</p>
                        <p class="text-gray-600 mb-2"><strong>Distancia (km):</strong> {{ props.place.distancia_km }}</p>
                        <p class="text-gray-600 mb-2"><strong>Entrada:</strong> {{ props.place.entrada === 'P'? 'Pagado' : props.place.entrada === 'L' ? 'Libre' : 'Restringido' }}</p>
                        <p class="text-gray-600 mb-2"><strong>Tipo de acceso:</strong> {{ props.place.tipo_acceso }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <MapComponent :initial-lat="props.place.latitud" :initial-lng="props.place.longitud" height="400px" :draggable="false" />
            </div>
            <div class="text-center mt-8">
                <button @click="visitPlace" class="inline-block bg-[#64161d] text-white py-2 px-4 rounded-md hover:bg-[#e8a860]">Visitar</button>
            </div>
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-center mb-8 text-[#64161d]">Lugares Recomendados</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="recommendedPlace in props.recommendedPlaces" :key="recommendedPlace.id" class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img :src="recommendedPlace.photos.length ? recommendedPlace.photos[0].url : '/path/to/default-image.jpg'" :alt="recommendedPlace.nombre" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-[#64161d] mb-2">{{ recommendedPlace.nombre }}</h3>
                            <p class="text-gray-600 mb-2">
                            <strong>Ubicación: </strong>
                            {{ recommendedPlace.district.province.name }} /
                            {{ recommendedPlace.district.name }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            <strong>Época de visita:</strong>
                            {{ recommendedPlace.epoca_visita }}
                        </p>
                        <p class="text-gray-600 mb-4">
                            <strong>Días abierto:</strong>
                            {{ recommendedPlace.dias_abierto_desde }} -
                            {{ recommendedPlace.dias_cerrado_hasta }}
                        </p>
                        <p class="text-gray-600 mb-4">
                            <strong>Distancia: </strong>
                            {{ recommendedPlace.distancia_horas }} horas
                        </p>
                        <p class="text-gray-600 mb-4">
                            <strong>LLegada: </strong> {{ recommendedPlace.tipo_acceso }}
                            <v-icon
                                class="text-[#64161d]"
                                name="ri-footprint-fill"
                            />
                            <v-icon
                                v-if="recommendedPlace.tipo_acceso == 'total'"
                                class="text-[#64161d]"
                                name="fa-car-side"
                            />
                        </p>
                            <Link :href="route('placesclient.show', recommendedPlace.id)" class="inline-block bg-[#64161d] text-white py-2 px-4 rounded-md hover:bg-[#e8a860]">Ver Detalles</Link>
                        </div>
                    </div>
                </div>
            </div>
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

/* Estilos para el carrusel */
.carousel {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
}

.carousel-item {
    flex: none;
    scroll-snap-align: start;
    margin-right: 1rem;
}
</style>
