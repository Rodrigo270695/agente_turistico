<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, defineProps } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import PlaceForm from './PlaceForm.vue';
import Swal from "sweetalert2";

let placeObj = ref(null);
let showModal = ref(false);
let photoModal = ref(false);

const props = defineProps({
    departments: Array,
    subCategories: Array,
    places: Object,
    text: String,
});

let query = ref(props.text);

const form = useForm({
    place: Object,
});

const addPlace = () => {
    placeObj.value = null;
    showModal.value = true;
};

const addPhoto = (place) => {
    placeObj.value = place;
    photoModal.value = true;
};

const editPlace = (place) => {
    placeObj.value = place;
    showModal.value = true;
};

const closeModal = () => {
    placeObj.value = null;
    showModal.value = false;
    photoModal.value = false;
};

const deletePlace = (place) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿De eliminar el lugar: " + place.name,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("places.destroy", place), {
                preserveScroll: true,
            });
        }
    });
};
</script>

<template>
    <AppLayout title="Lugares">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestionar Lugares
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="query"
                                class="w-auto lg:w-96 hover:border-sky-300 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                                placeholder="Buscar Lugar"
                                @keyup.enter="search"
                            />
                            <button
                                @click.prevent="refresh"
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
                                @click="addPlace"
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
                                            Lugar
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Categoría
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Atención
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Horario
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            estado
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr
                                        v-for="place in places.data"
                                        :key="place.id"
                                    >
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{
                                                place.district.province
                                                    .department.name
                                            }}/{{
                                                place.district.province.name
                                            }}/{{ place.district.name }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{
                                                place.subcategory.typecategory
                                                    .nombre
                                            }}/{{ place.subcategory.nombre }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{ place.dias_abierto_desde }} -
                                            {{ place.dias_cerrado_hasta }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            {{
                                                new Date(
                                                    "1970-01-01T" +
                                                        place.hora_apertura +
                                                        "Z"
                                                ).getUTCHours()
                                            }}:{{
                                                new Date(
                                                    "1970-01-01T" +
                                                        place.hora_apertura +
                                                        "Z"
                                                )
                                                    .getUTCMinutes()
                                                    .toString()
                                                    .padStart(2, "0")
                                            }}
                                            -
                                            {{
                                                new Date(
                                                    "1970-01-01T" +
                                                        place.hora_cierre +
                                                        "Z"
                                                ).getUTCHours()
                                            }}:{{
                                                new Date(
                                                    "1970-01-01T" +
                                                        place.hora_cierre +
                                                        "Z"
                                                )
                                                    .getUTCMinutes()
                                                    .toString()
                                                    .padStart(2, "0")
                                            }}
                                        </td>
                                        <td
                                            class="text-xs md:text-sm px-6 py-3 whitespace-nowrap text-center"
                                        >
                                            <p
                                                class="inline-block px-2 rounded-full h-auto justify-center items-center text-xs md:text-sm"
                                                :class="{
                                                    ' bg-green-500 text-white':
                                                        place.estado == 1,
                                                    'bg-red-500 rounded text-white':
                                                        place.estado == 0,
                                                }"
                                            >
                                                {{
                                                    place.estado == 1
                                                        ? "ACTIVO"
                                                        : "INACTIVO"
                                                }}
                                            </p>
                                        </td>

                                        <td
                                            class="text-xs md:text-sm px-6 py-4 whitespace-nowrap"
                                        >
                                            <!--                                             <button
                                                class="bg-green-500 text-white p-1 rounded-full hover:bg-green-600 cursor-pointer mr-1"
                                                @click="addPhoto(place)"
                                            >
                                                <v-icon
                                                    name="md-addphotoalternate-round"
                                                />
                                            </button>
                                            <button
                                                class="bg-yellow-500 text-white p-1 rounded-full hover:bg-yellow-600 cursor-pointer mr-1"
                                                @click="editPlace(place)"
                                            >
                                                <v-icon
                                                    name="md-modeedit-round"
                                                />
                                            </button>
                                            <button
                                                class="text-white p-1 rounded-full bg-red-400 hover:bg-red-500"
                                                @click.prevent="deletePlace(place)"
                                            >
                                                <v-icon
                                                    name="bi-trash"
                                                />
                                            </button> -->
                                        </td>
                                    </tr>
                                    <tr v-if="places.data.length <= 0">
                                        <td class="text-center" colspan="5">
                                            No hay registros
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination class="mt-2" :pagination="places" />
                        </div>
                    </div>

                    <Modal :show="showModal" @close="showModal = false">
                        <PlaceForm :subCategories="subCategories" :departments="departments" :place="placeObj" @close-modal="closeModal" />
                    </Modal>

<!--                     <Modal :show="photoModal" @close="photoModal = false">
                        <UploadImage :place="placeObj" @close-modal="closeModal"/>
                    </Modal> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
