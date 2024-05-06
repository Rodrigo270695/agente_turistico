<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps} from "vue";

const props = defineProps({
    subCategory: Object,
    typeCategories: Array,
});

const form = useForm({
    id: props.subCategory ? props.subCategory.id : "",
    nombre: props.subCategory ? props.subCategory.nombre : "",
    type_category_id: props.subCategory ? props.subCategory.type_category_id : "",
});

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const submit = () => {
    if (props.subCategory) {
        form.put(route('subcategorias.update', props.subCategory), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route("subcategorias.store"), {
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
                {{ form.id == 0 ? "Registrar Sub Categoria" : "Actualizar Sub Categoria" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Tipo Categoría" />
                        <select
                            id="select"
                            v-model="form.type_category_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-4000 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="sub in props.typeCategories"
                                :key="sub.id"
                                :value="sub.id"
                            >
                                {{ sub.category.nombre }}->{{ sub.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.type_category_id"
                        />
                    </div>
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
