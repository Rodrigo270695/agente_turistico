<script setup>
import { useForm } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    place: Object,
    price: Object,
});

const form = useForm({
    tipo_persona: '',
    precio: '',
    place_id: props.place.id,
});

const submit = () => {
    form.post(route("prices.store"), {
        preserveScroll: true,
        onSuccess: () => emit("close-modal"),
    });
};

const emit = defineEmits(["close-modal"]);

const tiposDePersona = [
    { id: 1, nombre: 'Estudiante' },
    { id: 2, nombre: 'Adulto' },
    { id: 3, nombre: 'Preferencial' }
];


</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div
                class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2"
            >
                Registrar precio a los tipos de persona
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <InputLabel value="Tipo de persona" />
                        <select
                            id="select"
                            v-model="form.tipo_persona"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-4000 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="tipo in tiposDePersona"
                                :key="tipo.id"
                                :value="tipo.nombre"
                            >
                                {{ tipo.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.tipo_persona"
                        />
                    </div>
                    <div class="col-span-2">
                        <InputLabel value="Precio" />
                        <TextInput
                            type="number"
                            class="w-full"
                            v-model="form.precio"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.precio"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-end">
                <button
                    class="bg-blue-500 text-white px-4 py-2 rounded-md "
                    :disabled="form.processing"
                >
                    Registrar
                </button>
            </div>
        </div>
    </form>
</template>
