<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, defineProps } from "vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import Swal from "sweetalert2";
import PriceForm from "./PriceForm.vue";

let showModal = ref(false);

const props = defineProps({
    place: Object,
});

const form = useForm({
    place: props.place,
});

const addPrice = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const deletePrice = (price) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿De eliminar la foto",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route("prices.destroy", price), {
                preserveScroll: true,
            });
        }
    });
};

const goToIndex = () => {
    form.get(route("lugares.index"));
};


</script>

<template>
    <AppLayout title="Fotos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Precio para 
                <strong class="text-sky-800 font-bold">{{
                    place.nombre
                }}</strong>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between py-2 px-4 mr-4 mt-4">
                        <div class="relative">
                            <button
                                class="bg-slate-600 hover:bg-slate-500 p-2 text-white rounded-lg flex items-center"
                                @click="goToIndex()"
                            >
                                <v-icon name="md-arrowbackios-round" />
                                Regresar
                            </button>
                        </div>
                        <div>
                            <button
                                class="bg-sky-900 hover:bg-sky-800 p-2 text-white rounded-lg flex items-center"
                                @click="addPrice"
                            >
                                <v-icon name="io-add-circle-sharp" />
                                <p class="sm:block hidden ml-2">agregar</p>
                            </button>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="overflow-x-auto rounded-lg">
                            <div
                                class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4"
                            >
                                <div
                                    v-for="price in place.prices"
                                    :key="price.id"
                                    class="relative card"
                                >

                                    <button
                                        @click="deletePrice(price)"
                                        class="absolute right-2 top-2 bg-red-400 hover:bg-red-500 text-white font-bold p-2 rounded-full"
                                    >
                                        <v-icon name="md-delete" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Modal :show="showModal" @close="showModal = false">
                        <PriceForm :place="place" @close-modal="closeModal" />
                    </Modal>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
