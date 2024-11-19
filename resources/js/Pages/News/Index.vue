<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import NewsForm from './NewsForm.vue';
import Swal from "sweetalert2";

let newsObj = ref(null);
let showModal = ref(false);

const props = defineProps({
    news: Object,
    departments: Array,
    texto: String,
});

let query = ref(props.texto);

const form = useForm({
    news: Object,
});

const addNews = () => {
    newsObj.value = null;
    showModal.value = true;
};

const editNews = (news) => {
    newsObj.value = news;
    showModal.value = true;
};

const closeModal = () => {
    newsObj.value = null;
    showModal.value = false;
};

const changeStatus = (news) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de la Noticia?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("noticias.change", news), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("noticias.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("news.index"));
};


const asset = (path) => {
    return `/storage/${path}`;
};

</script>

<template>
    <AppLayout title="Noticias">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Noticias
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-36 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-auto lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                placeholder="Buscar Noticia"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="goToIndex"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-white bg-sky-900 rounded-e-md hover:bg-sky-800"
                            >
                                <v-icon
                                    name="io-reload-circle-sharp"
                                    scale="1.5"
                                />
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-sky-900 hover:bg-sky-800 p-2 text-white rounded-lg flex items-center"
                                @click="addNews"
                            >
                                <v-icon name="io-add-circle-sharp" />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-sky-200">
                                <thead class="bg-sky-50">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Título
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Imagen
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Lugar
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Contenido
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Fecha Fin
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Estado
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="news in news.data"
                                        :key="news.id"
                                    >
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ news.title }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <img
                                                :src="news.image ? asset(news.image) : asset('default.png')"
                                                alt="Imagen"
                                                class="w-16 h-16 object-cover rounded-md mx-auto"
                                                    />
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ news.district.province.department.name + '/' + news.district.province.name + '/' + news.district.name }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ news.content }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ news.date_end }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-xs md:text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        news.estado == 1,
                                                    'bg-red-500 rounded text-white':
                                                        news.estado == 0,
                                                }"
                                            >
                                                {{
                                                    news.estado == 1
                                                        ? "Publicado"
                                                        : "Borrador"
                                                }}
                                            </p>
                                        </td>

                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <button
                                                class="bg-yellow-500 text-white p-1 rounded-full hover:bg-yellow-600 cursor-pointer mr-1"
                                                @click="editNews(news)"
                                            >
                                                <v-icon
                                                    name="md-modeedit-round"
                                                />
                                            </button>
                                            <button
                                                class="text-white p-1 rounded-full"
                                                :class="{
                                                    'bg-orange-500 hover:bg-orange-400':
                                                        news.estado == 1,
                                                    'bg-green-500 hover:bg-green-400':
                                                        news.estado == 0,
                                                }"
                                                @click="changeStatus(news)"
                                            >
                                                <v-icon
                                                    v-if="news.estado == 1"
                                                    name="gi-cancel"
                                                />
                                                <v-icon
                                                    v-else
                                                    name="fa-check"
                                                />
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="news.data.length <= 0">
                                        <td class="text-center" colspan="5">
                                            No hay registros
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination
                            class="mt-2"
                            :pagination="news"
                        />
                    </div>

                    <Modal :show="showModal" @close="showModal = false" maxWidth="3xl">
                        <NewsForm :news="newsObj" :departments="departments" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
