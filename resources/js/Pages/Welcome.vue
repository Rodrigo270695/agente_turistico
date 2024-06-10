<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Bienvenido" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navbar -->
        <nav class="bg-red-600 text-white">
            <div class="container mx-auto flex justify-between items-center p-4">
                <div class="flex items-center">
                    <img src="/path/to/logo.png" alt="Logo" class="h-8 mr-2">
                    <span class="text-xl font-bold">Logo</span>
                </div>
                <div class="flex items-center space-x-4">
                    <Link href="/login" class="hover:underline0">Iniciar Sesión</Link>
                    <Link href="/register" class="hover:underline">Registrarse</Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="relative flex justify-center items-center py-4 bg-cover bg-center" style="background-image: url('/path/to/background-image.jpg');">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative z-10 text-center text-white">
                <h1 class="text-4xl font-bold mb-4">Encuentra la visita perfecta</h1>
                <div class="bg-white p-6 rounded-md shadow-md text-gray-900">
                    <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="origen" class="block text-sm font-medium">Provincia</label>
                            <input type="text" id="origen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50" placeholder="Ciudad de origen">
                        </div>
                        <div>
                            <label for="destino" class="block text-sm font-medium">Ciudad</label>
                            <input type="text" id="destino" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50" placeholder="Ciudad de destino">
                        </div>
                        <div>
                            <label for="categoria" class="block text-sm font-medium">Categoría</label>
                            <input type="text" id="categoria" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50" placeholder="Categoría">
                        </div>
                        <div>
                            <label for="tipo" class="block text-sm font-medium">Tipo</label>
                            <input type="text" id="tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50" placeholder="Tipo">
                        </div>
                        <div class="col-span-1 md:col-span-2 lg:col-span-4">
                            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-md hover:bg-red-700">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tourist Centers Section -->
        <div class="container mx-auto py-12">
            <h2 class="text-2xl font-bold text-center mb-8 text-white">Centros Turísticos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="/path/to/image1.jpg" alt="Centro Turístico 1" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold">Cartagena</h3>
                        <p class="text-gray-600">Desde USD370 | S/1,389</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="/path/to/image2.jpg" alt="Centro Turístico 2" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold">Santo Domingo</h3>
                        <p class="text-gray-600">Desde USD873 | S/3,278</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="/path/to/image3.jpg" alt="Centro Turístico 3" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold">Miami</h3>
                        <p class="text-gray-600">Desde USD390 | S/1,464</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="/path/to/image4.jpg" alt="Centro Turístico 4" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold">Londres</h3>
                        <p class="text-gray-600">Desde USD1,653 | S/6,214</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="/path/to/image5.jpg" alt="Centro Turístico 5" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold">Varadero</h3>
                        <p class="text-gray-600">Desde USD576 | S/2,163</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="/path/to/image6.jpg" alt="Centro Turístico 6" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-bold">Rio de Janeiro</h3>
                        <p class="text-gray-600">Desde USD653 | S/2,377</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
/* Estilos adicionales para mejorar la apariencia */
body {
    font-family: 'Arial', sans-serif;
}

nav a {
    transition: color 0.3s;
}

nav a:hover {
    color: #ffcc00;
}

.bg-cover {
    background-size: cover;
}

.bg-center {
    background-position: center;
}
</style>
