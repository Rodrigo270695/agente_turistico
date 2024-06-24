<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-between">
        <!-- Navbar -->
        <nav class="bg-[#64161d] text-white">
            <div class=" mx-20 flex justify-between items-center p-4">
                <div class="flex items-center">
                    <img src="/path/to/logo.png" alt="Logo" class="h-8 mr-2">
                    <span class="text-xl font-bold">Logo</span>
                </div>
                <div class="flex items-center space-x-4">
                    <Link :href="route('about')" class="hover:text-[#e8a860]" :class="{ 'text-[#e8a860]': activePage === 'about' }">Sobre Nosotros</Link>
                    <Link :href="route('places')" class="hover:text-[#e8a860]" :class="{ 'text-[#e8a860]': activePage === 'places' }">Lugares</Link>
                </div>
                <div class="flex items-center space-x-4">
                    <template v-if="$page.props.auth.user && $page.props.user.roles.includes('admin')">
                        <Link :href="route('dashboard')" class="hover:text-[#e8a860]" :class="{ 'text-[#e8a860]': activePage === 'dashboard' }">Dashboard</Link>
                    </template>
                    <a v-if="$page.props.auth.user" href="#">{{ $page.props.auth.user.name }}</a>
                    <template  v-else>
                        <Link :href="route('login')" class="hover:text-[#e8a860]" :class="{ 'text-[#e8a860]': activePage === 'login' }">Iniciar Sesión</Link>
                        <Link :href="route('register')" class="hover:text-[#e8a860]" :class="{ 'text-[#e8a860]': activePage === 'register' }">Registrarse</Link>
                    </template>
                    <form v-if="$page.props.auth.user" @submit.prevent="logout">
                        <DropdownLink class="hover:text-[#e8a860] cursor-pointer"   as="button">
                            Salir
                        </DropdownLink>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div >
            <slot></slot>
        </div>

        <!-- Footer -->
        <footer class="bg-[#64161d] text-white py-6">
            <div class="container mx-auto text-center">
                <p>&copy; 2023 Tu Empresa. Todos los derechos reservados.</p>
                <div class="flex justify-center space-x-4 mt-4">
                    <Link href="/about" class="hover:text-[#e8a860]">Sobre Nosotros</Link>
                    <Link href="/contact" class="hover:text-[#e8a860]">Contacto</Link>
                    <Link href="/privacy" class="hover:text-[#e8a860]">Política de Privacidad</Link>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import DropdownLink from '@/Components/DropdownLink.vue';
defineProps({
    activePage: String,
});

const logout = () => {
    router.post(route('logout'));
};
</script>

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
