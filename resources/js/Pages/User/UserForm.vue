<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";

const props = defineProps({
    user: Object,
});

const roles = [
    { id: 'admin', nombre: 'Administrador' },
    { id: 'visitante', nombre: 'Visitante' }
];

const form = useForm({
    id: props.user ? props.user.id : "",
    name: props.user ? props.user.name : "",
    last_name: props.user ? props.user.last_name : "",
    dni: props.user ? props.user.dni : "",
    phone: props.user ? props.user.phone : "",
    address: props.user ? props.user.address : "",
    email: props.user ? props.user.email : "",
    role: props.user ? props.user.roles[0].name : "",
});

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const submit = () => {
    if (props.user) {
        form.put(route('usuarios.update', props.user), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route("usuarios.store"), {
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
                {{ form.id == 0 ? "Registrar Usuario" : "Actualizar Usuario" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Rol" />
                        <select
                            id="select"
                            v-model="form.role"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        >
                            <option disabled selected value="">
                                Seleccione un rol
                            </option>
                            <option
                                v-for="role in roles"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.nombre }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.role"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Nombres" />
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                            @input="form.name = toTitleCase(form.name)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Apellidos" />
                        <TextInput
                            class="w-full"
                            v-model="form.last_name"
                            @input="form.last_name = toTitleCase(form.last_name)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.last_name"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Dni" />
                        <TextInput
                            class="w-full"
                            v-model="form.dni"
                            @input="form.dni = toTitleCase(form.dni)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.dni"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Celular" />
                        <TextInput
                            class="w-full"
                            v-model="form.phone"
                            @input="form.phone = toTitleCase(form.phone)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.phone"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Dirección" />
                        <TextInput
                            class="w-full"
                            v-model="form.address"
                            @input="form.address = toTitleCase(form.address)"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.address"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Correo Electrónico" />
                        <TextInput
                            class="w-full"
                            v-model="form.email"
                            type="email"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.email"
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
