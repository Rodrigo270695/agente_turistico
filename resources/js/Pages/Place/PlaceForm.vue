<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, defineProps, watch, onMounted } from "vue";

const props = defineProps({
    place: Object,
    departments: Array,
    subcategories: Array,
});

const selection = ref("no");
const selectedDepartment = ref(null);
const selectedProvince = ref(null);
const provinces = ref([]);
const districts = ref([]);
const selectedDistrict = ref(null);

const form = useForm({
    id: props.place ? props.place.id : "",
    nombre: props.place ? props.place.nombre : "",
    latitud: props.place ? props.place.latitud : "",
    longitud: props.place ? props.place.longitud : "",
    dias_abierto_desde: props.place ? props.place.dias_abierto_desde : "",
    dias_cerrado_hasta: props.place ? props.place.dias_cerrado_hasta : "",
    hora_apertura: props.place ? props.place.hora_apertura : "",
    hora_cierre: props.place ? props.place.hora_cierre : "",
    direccion: props.place ? props.place.direccion : "",
    tipo_acceso: props.place ? props.place.tipo_acceso : "",
    district_id: props.place ? props.place.district_id : null,
    sub_category_id: props.place ? props.place.sub_category_id : null,
});

watch(
    () => props.place,
    (newPlace) => {
        if (newPlace) {
            selectedDepartment.value = newPlace.department;
            const department = props.departments.find(
                (d) => d.name === selectedDepartment.value
            );
            if (department) {
                provinces.value = department.provinces;
                selectedProvince.value = newPlace.province;
                const province = department.provinces.find(
                    (p) => p.name === selectedProvince.value
                );
                if (province) {
                    districts.value = province.districts;
                    selectedDistrict.value = newPlace.district;
                }
            }
            form.id = newPlace.id;
            form.name = newPlace.name;
        }
    },
    { immediate: true }
);

watch(selectedDepartment, (newValue) => {
    if (newValue) {
        const selectedDepartment = props.departments.find(
            (department) => department.name === newValue
        );
        if (selectedDepartment) {
            provinces.value = selectedDepartment.provinces;
            form.department = newValue;
        } else {
            provinces.value = [];
            form.department = "";
        }
    } else {
        provinces.value = [];
        form.department = "";
    }
});

watch(selectedProvince, (newValue) => {
    if (newValue) {
        const department = props.departments.find(
            (department) => department.name === selectedDepartment.value
        );
        if (department) {
            const province = department.provinces.find(
                (province) => province.name === newValue
            );
            if (province) {
                districts.value = province.districts;
                form.province = newValue;
            } else {
                districts.value = [];
                form.province = "";
            }
        }
    } else {
        districts.value = [];
        form.province = "";
    }
});

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const submit = () => {
    if (props.place) {
        form.put(route("places.update", props.place.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("places.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const access = [
    { id: 1, name: "TOTAL" },
    { id: 2, name: "PARCIAL" },
];

const days = [
    { id: 1, name: "Lunes" },
    { id: 2, name: "Martes" },
    { id: 2, name: "Miércoles" },
    { id: 2, name: "Jueves" },
    { id: 2, name: "Viernes" },
    { id: 2, name: "Sábado" },
    { id: 2, name: "Domingo" },
];
</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div
                class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2"
            >
                {{
                    form.id == 0
                        ? "Registrar Lugar"
                        : "Actualizar Lugar"
                }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-8 gap-3">

                    <div class="col-span-8 sm:col-span-4">
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

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Departamento" />
                        <select
                            id="select"
                            v-model="selectedDepartment"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="department in departments"
                                :key="department.id"
                                :value="department.name"
                            >
                                {{ department.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.department"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <TextInput
                            type="hidden"
                            class="w-full"
                            v-model="form.department"
                            :value="selectedDepartment"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Provincia" />
                        <select
                            id="select"
                            v-model="selectedProvince"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="province in provinces"
                                :key="province.id"
                                :value="province.name"
                            >
                                {{ province.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.province"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <TextInput
                            type="hidden"
                            class="w-full"
                            v-model="form.province"
                            :value="selectedProvince"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Distrito" />
                        <select
                            id="select"
                            v-model="form.district_id"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="district in districts"
                                :key="district.id"
                                :value="district.name"
                            >
                                {{ district.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.district_id"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Dirección" />
                        <TextArea class="w-full" v-model="form.direccion" />
                        <InputError
                            class="w-full"
                            :message="form.errors.direccion"
                        />
                    </div>
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Acceso" />
                        <select
                            id="select"
                            v-model="form.tipo_acceso"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="dato in access"
                                :key="dato.id"
                                :value="dato.name"
                            >
                                {{ dato.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.tipo_acceso"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Días de atención" />
                        <div class="grid grid-cols-6 gap-3">
                            <div class="col-span-6 sm:col-span-3">
                                <select
                                    id="select"
                                    v-model="form.dias_abierto_desde"
                                    class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                >
                                    <option disabled selected value="">
                                        Seleccione un opción
                                    </option>
                                    <option
                                        v-for="day in days"
                                        :key="day.id"
                                        :value="day.name"
                                    >
                                        {{ day.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="w-full"
                                    :message="form.errors.dias_abierto_desde"
                                />
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <select
                                    id="select"
                                    v-model="form.dias_cerrado_hasta"
                                    class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                                >
                                    <option disabled selected value="">
                                        Seleccione un opción
                                    </option>
                                    <option
                                        v-for="day in days"
                                        :key="day.id"
                                        :value="day.name"
                                    >
                                        {{ day.name }}
                                    </option>
                                </select>
                                <InputError
                                    class="w-full"
                                    :message="form.errors.dias_cerrado_hasta"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Horas de atención" />
                        <TextInput
                            class="w-full"
                            v-model="form.opening_hours"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.opening_hours"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Precio" />
                        <TextInput
                            type="number"
                            class="w-full"
                            v-model="form.price"
                        />
                        <InputError
                            class="w-full"
                            :message="form.errors.price"
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
