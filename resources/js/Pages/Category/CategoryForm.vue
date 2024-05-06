<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps} from "vue";

const props = defineProps({
    category: Object,
});

const form = useForm({
    id: props.category ? props.category.id : "",
    nombre: props.category ? props.category.nombre : "",
});

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const submit = () => {
    if (props.category) {
        form.put(route('categorias.update', props.category), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal') 
        });
    } else {
        form.post(route("categorias.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2">
                {{ form.id == 0 ? "Registrar Categoria" : "Actualizar Categoria" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nombre" />
                        <TextInput
                            class="w-full"
                            v-model="form.nombre"
                            @input="form.nombre = toTitleCase(form.nombre)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.nombre"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2"
                    :disabled="form.processing"
                >
                    {{ form.id == 0 ? "Registrar" : "Actualizar" }}
                </button>
            </div>
        </div>
    </form>
</template>
