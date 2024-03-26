<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    place: Object
});

const form = useForm({
    photos: [],
    place_id: props.place ? props.place.id : "",
});

const images = ref([]);
const imageFiles = ref([]);

// Función para previsualizar las imágenes seleccionadas
const handleFileChange = (event) => {
    const files = event.target.files;
    processFiles(files);
};

// Función para procesar los archivos seleccionados o arrastrados
const processFiles = (files) => {
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        imageFiles.value.push(file);
        const reader = new FileReader();
        reader.onload = (e) => {
            images.value.push({ url: e.target.result, name: file.name });
        };
        reader.readAsDataURL(file);
    }
};

// Función para eliminar una imagen seleccionada
const removeImage = (index) => {
    images.value.splice(index, 1);
    imageFiles.value.splice(index, 1);
};

// Función para manejar el evento de soltar archivos
const handleDrop = (event) => {
    const files = event.dataTransfer.files;
    processFiles(files);
};

const emit = defineEmits(["close-modal"]);

const uploadImages = () => {
    if (imageFiles.value.length === 0) {
        console.error("No hay imágenes seleccionadas para subir.");
        return;
    }

    let formData = new FormData();
    formData.append('place_id', props.place.id);
    for (let i = 0; i < imageFiles.value.length; i++) {
        formData.append('photos[]', imageFiles.value[i]);
    }

    Inertia.post(route('photos.store'), formData, {
        onBefore: () => {
            // Opcional: acciones antes de enviar la solicitud
        },
        onSuccess: () => {
            emit('close-modal')
        },
        onError: (error) => {
            console.error("Error al subir las imágenes:", error);
        }
    });
};


</script>

<template>
    <div class="p-4">
        <form @submit.prevent="uploadImages">
            <div
                @drop.prevent="handleDrop"
                @dragover.prevent
                class="mt-4 border-2 border-dashed border-gray-300 rounded-lg p-4 text-center"
            >
                <input
                    type="file"
                    multiple
                    @change="handleFileChange"
                    class="hidden"
                    ref="fileInput"
                />
                <button
                    type="button"
                    @click="$refs.fileInput.click()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                >
                    <v-icon name="bi-upload" class="w-6 h-6 mr-2"></v-icon>
                    Seleccionar imágenes
                </button>
                <p class="text-gray-500 mt-2">O arrastra las imágenes aquí</p>
            </div>
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div
                    v-for="(image, index) in images"
                    :key="index"
                    class="relative"
                >
                    <img
                        :src="image.url"
                        :alt="image.name"
                        :title="image.name"
                        class="w-full h-32 object-cover rounded-lg"
                    />
                    <button
                        type="button"
                        @click="removeImage(index)"
                        class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-full"
                    >
                        <v-icon name="md-delete" class="w-6 h-6"></v-icon>
                    </button>
                </div>
            </div>
            <button
                type="submit"
                class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Subir imágenes
            </button>
        </form>
    </div>
</template>

<style scoped></style>
