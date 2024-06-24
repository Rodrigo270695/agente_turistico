<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from './Layout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Layout>
        <Head title="Bienvenido" />

        <div class="container mx-auto py-12">
        <h1 class="text-4xl font-bold text-center mb-8 text-[#64161d]">Sobre Nosotros</h1>
        <div class="bg-white p-6 rounded-md shadow-md text-gray-900">
            <p class="mb-4">
                Somos una empresa dedicada a ofrecer las mejores experiencias turísticas. Nuestro objetivo es ayudarte a encontrar la visita perfecta, ya sea que busques aventura, relajación o cultura.
            </p>
            <p class="mb-4">
                Con años de experiencia en la industria del turismo, nos enorgullecemos de ofrecer un servicio de alta calidad y atención personalizada. Trabajamos con los mejores proveedores para asegurarnos de que tu viaje sea inolvidable.
            </p>
            <p class="mb-4">
                Nuestro equipo está compuesto por profesionales apasionados por los viajes y comprometidos con la satisfacción del cliente. Estamos aquí para ayudarte en cada paso del camino, desde la planificación hasta la ejecución de tu viaje.
            </p>
            <p class="mb-4">
                Gracias por elegirnos como tu guía de viajes. ¡Esperamos ayudarte a descubrir el mundo!
            </p>
        </div>
    </div>
    </Layout>
</template>

<style>
.container {
    max-width: 800px;
}
</style>
