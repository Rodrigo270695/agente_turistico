<script setup>
import { ref } from 'vue';
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

// Estado para controlar el modal
const showModal = ref(false);
const selectedImage = ref('');

const openModal = (imageUrl) => {
    selectedImage.value = imageUrl;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};
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
                            <img :src="photo.url" :alt="props.place.nombre" class="w-full h-64 object-cover cursor-pointer" @click="openModal(photo.url)">
                        </div>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                                <strong>Provincia/Distrito:</strong> {{ props.place.district.province.name }} / {{ props.place.district.name }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13z"/>
                                </svg>
                                <strong>Época de visita:</strong> {{ props.place.epoca_visita }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                                <strong>Dirección:</strong> {{ props.place.direccion }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 11h-8V4H4v16h16V11zm-2 9H6V6h6v5h6v9z"/>
                                </svg>
                                <strong>Días abierto:</strong> {{ props.place.dias_abierto_desde }} - {{ props.place.dias_cerrado_hasta }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <strong>Hora de apertura:</strong> {{ props.place.hora_apertura }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <strong>Hora de cierre:</strong> {{ props.place.hora_cierre }}
                            </p>
                            <template v-if="props.place.prices.length > 0">
                                <p class="text-gray-600 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <strong>Precio: </strong>
                                    <div class="flex space-x-4">
                                        <template v-for="price in props.place.prices" :key="price.id">
                                            <span v-if="price.tipo_persona === 'Adulto'">Adulto: {{ price.precio }}</span>
                                            <span v-if="price.tipo_persona === 'Estudiante'">Estudiante: {{ price.precio }}</span>
                                            <span v-if="price.tipo_persona === 'Preferencial'">Preferencial: {{ price.precio }}</span>
                                        </template>
                                    </div>
                                </p>
                            </template>
                            <template v-else>
                                <p class="text-gray-600 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <strong>Precio: </strong>Gratis
                                </p>
                            </template>
                        </div>
                        <div>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                                <strong>Latitud:</strong> {{ props.place.latitud }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                                <strong>Longitud:</strong> {{ props.place.longitud }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M3 21l18-18" />
                                </svg>
                                <strong>Distancia (horas):</strong> {{ props.place.distancia_horas }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M3 21l18-18" />
                                </svg>
                                <strong>Distancia (km):</strong> {{ props.place.distancia_km }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <strong>Entrada:</strong> {{ props.place.entrada === 'P'? 'Pagado' : props.place.entrada === 'L' ? 'Libre' : 'Restringido' }}
                            </p>
                            <p class="text-gray-600 mb-2">
                                <strong>Tipo de acceso:</strong> {{ props.place.tipo_acceso }}
                            </p>
                        </div>
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
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                                </svg>
                                <strong>Ubicación: </strong>
                                {{ recommendedPlace.district.province.name }} /
                                {{ recommendedPlace.district.name }}
                            </p>
                            <p class="text-gray-600 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13z"/>
                                </svg>
                                <strong>Época de visita:</strong>
                                {{ recommendedPlace.epoca_visita }}
                            </p>
                            <p class="text-gray-600 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 11h-8V4H4v16h16V11zm-2 9H6V6h6v5h6v9z"/>
                                </svg>
                                <strong>Días abierto:</strong>
                                {{ recommendedPlace.dias_abierto_desde }} -
                                {{ recommendedPlace.dias_cerrado_hasta }}
                            </p>
                            <p class="text-gray-600 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M3 21l18-18" />
                                </svg>
                                <strong>Distancia: </strong>
                                {{ recommendedPlace.distancia_horas }} horas
                            </p>
                            <template v-if="recommendedPlace.prices.length > 0">
                                <p class="text-gray-600 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <strong>Precio: </strong>
                                    <div class="flex space-x-4">
                                        <template v-for="price in recommendedPlace.prices" :key="price.id">
                                            <span v-if="price.tipo_persona === 'Adulto'">
                                                Adulto: {{ price.precio }}
                                            </span>
                                            <span v-if="price.tipo_persona === 'Estudiante'">
                                                Estudiante: {{ price.precio }}
                                            </span>
                                            <span v-if="price.tipo_persona === 'Preferencial'">
                                                Preferencial: {{ price.precio }}
                                            </span>
                                        </template>
                                    </div>
                                </p>
                            </template>
                            <template v-else>
                                <p class="text-gray-600 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <strong>Precio: </strong>Gratis
                                </p>
                            </template>
                            <p class="text-gray-600 mb-4">
                                <strong>LLegada: </strong> {{ recommendedPlace.tipo_acceso }}
                                <v-icon
                                    v-if="recommendedPlace.tipo_acceso == 'PARCIAL'"
                                    class="text-[#64161d]"
                                    name="ri-footprint-fill"
                                />
                                <v-icon
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

    <!-- Modal para mostrar la imagen en grande -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-0 rounded-lg shadow-lg relative z-60">
            <button @click="closeModal" class="absolute top-2 right-2 text-white bg-red-500 rounded-md p-1">X</button>
            <img :src="selectedImage" alt="Imagen grande" class="max-w-full max-h-screen">
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
