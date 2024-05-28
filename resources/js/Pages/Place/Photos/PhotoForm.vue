<script setup>
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    place: Object,
    photo: Object,
});


const form = useForm({
    url: '',
    place_id: props.place.id,
});

const onFileChange = (event) => {
    form.url = event.target.files[0];
};

const submit = () => {
    form.post(route("photos.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div
                class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2"
            >
                Registrar Foto
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <InputLabel value="Suba la imagen" class="mb-2" />
                        <input
                            type="file"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                            @change="onFileChange"
                            ref="image"
                            accept="image/*"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.url"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-md"
                    :disabled="form.processing"
                >
                    Registrar
                </button>
            </div>
        </div>
    </form>
</template>
