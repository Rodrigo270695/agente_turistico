<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import CategoryForm from './CategoryForm.vue';
import Swal from "sweetalert2";

let categoryObj = ref(null);
let showModal = ref(false);

const props = defineProps({
    categories: Object,
    texto: String,
});

let query = ref(props.texto);

const form = useForm({
    category: Object,
});

const addCategory = () => {
    categoryObj.value = null;
    showModal.value = true;
};

const editCategory = (category) => {
    categoryObj.value = category;
    showModal.value = true;
};

const closeModal = () => {
    categoryObj.value = null;
    showModal.value = false;
};


const changeStatus = (category) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Quieres cambiar el estado de la Categoría?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cambiar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.put(route("categorias.change", category), {
                preserveScroll: true,
            });
        }
    });
};

const search = () => {
    form.get(route("categorias.search", { texto: query.value }));
};

const goToIndex = () => {
    form.get(route("categorias.index"));
};

</script>

<template>
    <AppLayout title="Categorias">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Categorias
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
                                placeholder="Buscar Categoria"
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
                                @click="addCategory"
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
                                            Nombre
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
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
                                        v-for="category in categories.data"
                                        :key="category.id"
                                    >
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ category.nombre }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-xs md:text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        category.estado == 1,
                                                    'bg-red-500 rounded text-white':
                                                        category.estado == 0,
                                                }"
                                            >
                                                {{
                                                    category.estado == 1
                                                        ? "ACTIVO"
                                                        : "INACTIVO"
                                                }}
                                            </p>
                                        </td>

                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >

                                            <button
                                                class="bg-yellow-500 text-white p-1 rounded-full hover:bg-yellow-600 cursor-pointer mr-1"
                                                @click="editCategory(category)"
                                            >
                                                <v-icon
                                                    name="md-modeedit-round"
                                                />
                                            </button>
                                            <button
                                                            class="text-white p-1 rounded-full"
                                                            :class="{
                                                                'bg-orange-500 hover:bg-orange-400':
                                                                    category.estado ==
                                                                    1,
                                                                'bg-green-500 hover:bg-green-400':
                                                                    category.estado ==
                                                                    0,
                                                            }"
                                                            @click="
                                                                changeStatus(category)
                                                            "
                                                        >
                                                            <v-icon
                                                                v-if="
                                                                    category.estado ==
                                                                    1
                                                                "
                                                                name="gi-cancel"
                                                            />
                                                            <v-icon
                                                                v-else
                                                                name="fa-check"
                                                            />
                                                        </button>
                                        </td>
                                    </tr>
                                    <tr v-if="categories.data.length <= 0">
                                        <td class="text-center" colspan="3">
                                            No hay registros
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination
                            class="mt-2"
                            :pagination="categories"
                        />
                    </div>

                    <Modal :show="showModal" @close="showModal = false" maxWidth="xl">
                        <CategoryForm  :category="categoryObj" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
