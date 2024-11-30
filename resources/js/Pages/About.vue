<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from './Layout.vue';
import { defineProps, ref } from 'vue';

const props = defineProps({
    news: Array, // Asegúrate de pasar las noticias como una propiedad
});

const showModal = ref(false);
const selectedImage = ref(null);

const openModal = (image) => {
    selectedImage.value = image;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedImage.value = null;
};
</script>

<template>
    <Layout>
        <Head title="Noticias" />

        <div class="container mx-auto py-12">
            <h2 class="text-4xl font-bold text-center mb-10 text-[#64161d] mt-6">Últimas Noticias</h2>
            <div class="news-feed">
                <div v-for="item in news" :key="item.id" class="news-item bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 mb-8">
                    <h3 class="text-2xl font-semibold mb-2 text-[#333]">{{ item.title }}</h3>
                    <p class="text-sm text-gray-500 mb-4">Fecha de publicación: {{ new Date(item.created_at).toLocaleDateString() }}</p>
                    <p class="mb-4 text-gray-700">{{ item.content }}</p>
                    <img v-if="item.image" :src="`/storage/${item.image}`" alt="Imagen de la noticia" class="w-full h-auto rounded-md mb-4 cursor-pointer" @click="openModal(item.image)" />
                    <p class="text-right text-sm text-gray-500">Publicado en: {{ item.district.province.department.name }}</p>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full">
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700 float-right">&times;</button>
                <img :src="`/storage/${selectedImage}`" alt="Imagen completa" class="w-full h-auto rounded-md" />
            </div>
        </div>
    </Layout>
</template>

<style>
.container {
    max-width: 800px;
    padding: 0 20px;
}

.news-feed {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.news-item {
    border: 1px solid #e2e8f0;
    transition: transform 0.3s, box-shadow 0.3s;
    overflow: hidden;
    position: relative;
}

.news-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
}

.news-item h3 {
    color: #2c3e50;
}

.news-item p {
    line-height: 1.6;
}

.news-item img {
    max-height: 200px;
    object-fit: cover;
    transition: transform 0.3s;
}

.news-item:hover img {
    transform: scale(1.05);
}
</style>
