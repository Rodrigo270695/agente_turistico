<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import MapComponent from "@/Components/MapComponent.vue";
import { useForm } from "@inertiajs/vue3";
import 'leaflet/dist/leaflet.css';
import { ref, defineProps, watch, onMounted } from "vue";

const props = defineProps({
    place: Object,
    departments: Array,
    subCategories: Array,
});

const selection = ref("no");
const selectedDepartment = ref("LAMBAYEQUE");
const selectedProvince = ref(null);
const provinces = ref([]);
const districts = ref([]);
const selectedDistrict = ref(null);

const form = useForm({
    id: props.place ? props.place.id : "",
    nombre: props.place ? props.place.nombre : "",
    latitud: props.place ? props.place.latitud : '',
    longitud: props.place ? props.place.longitud : '',
    dias_abierto_desde: props.place ? props.place.dias_abierto_desde : "",
    dias_cerrado_hasta: props.place ? props.place.dias_cerrado_hasta : "",
    hora_apertura: props.place ? props.place.hora_apertura : "",
    hora_cierre: props.place ? props.place.hora_cierre : "",
    direccion: props.place ? props.place.direccion : "",
    tipo_acceso: props.place ? props.place.tipo_acceso : "",
    district_id: props.place ? props.place.district_id : '',
    sub_category_id: props.place ? props.place.sub_category_id : '',
});

onMounted(() => {
    if (props.place && props.place.district) {
        const department = props.departments.find(d => d.id === props.place.district.province.department_id);
        if (department) {
            selectedDepartment.value = department.name;
            provinces.value = department.provinces;
            const province = department.provinces.find(p => p.id === props.place.district.province_id);
            if (province) {
                selectedProvince.value = province.name;
                districts.value = province.districts;
                const district = districts.value.find(d => d.id === props.place.district_id);
                if (district) {
                    selectedDistrict.value = district.name;
                }
            }
        }
    } else {
        selectedDepartment.value = "LAMBAYEQUE";
        const initialDepartment = props.departments.find(d => d.name === selectedDepartment.value);
        if (initialDepartment) {
            provinces.value = initialDepartment.provinces;
        }
    }
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
        form.put(route("lugares.update", props.place.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("lugares.store"), {
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

const updateLat = (newLat) => {
  form.latitud = newLat;
};

const updateLng = (newLng) => {
  form.longitud = newLng;
};

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2">
                {{ form.id == 0 ? "Registrar Lugar" : "Actualizar Lugar" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <InputLabel value="Provincia" />
                        <select v-model="selectedProvince" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione un opción</option>
                            <option v-for="province in provinces" :key="province.id" :value="province.name">{{ province.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.province" />
                    </div>

                    <div>
                        <InputLabel value="Distrito" />
                        <select v-model="form.district_id" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione un opción</option>
                            <option v-for="district in districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.district_id" />
                    </div>

                    <div>
                        <InputLabel value="Nombre" />
                        <TextInput class="w-full" v-model="form.nombre" @input="form.nombre = toTitleCase(form.nombre)" />
                        <InputError class="w-full" :message="form.errors.nombre" />
                    </div>

                    <div>
                        <InputLabel value="Tipo de lugar" />
                        <select v-model="form.sub_category_id" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione un opción</option>
                            <option v-for="sub in subCategories" :key="sub.id" :value="sub.id">
                                {{sub.typecategory.category.nombre}}/{{sub.typecategory.nombre}}/{{ sub.nombre }}
                            </option>
                        </select>
                        <InputError class="w-full" :message="form.errors.sub_category_id" />
                    </div>


                    <div class="sm:col-span-2">
                        <InputLabel value="Dirección" />
                        <TextArea class="w-full" v-model="form.direccion" />
                        <InputError class="w-full" :message="form.errors.direccion" />
                    </div>

                    <div class="sm:col-span-2">
                        <InputLabel value="Días de atención" />
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <select v-model="form.dias_abierto_desde" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                    <option disabled selected value="">Seleccione un opción</option>
                                    <option v-for="day in days" :key="day.id" :value="day.name">{{ day.name }}</option>
                                </select>
                                <InputError class="w-full" :message="form.errors.dias_abierto_desde" />
                            </div>
                            <div>
                                <select v-model="form.dias_cerrado_hasta" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                    <option disabled selected value="">Seleccione un opción</option>
                                    <option v-for="day in days" :key="day.id" :value="day.name">{{ day.name }}</option>
                                </select>
                                <InputError class="w-full" :message="form.errors.dias_cerrado_hasta" />
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <InputLabel value="Horario de atención" />
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <TextInput type="time" class="w-full" v-model="form.hora_apertura" />
                                <InputError class="w-full" :message="form.errors.hora_apertura" />
                            </div>
                            <div>
                                <TextInput type="time" class="w-full" v-model="form.hora_cierre" />
                                <InputError class="w-full" :message="form.errors.hora_cierre" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <InputLabel value="Acceso" />
                        <select v-model="form.tipo_acceso" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione un opción</option>
                            <option v-for="dato in access" :key="dato.id" :value="dato.name">{{ dato.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.tipo_acceso" />
                    </div>

                    <div >
                        <InputLabel value="Latitud" />
                        <TextInput class="w-full" v-model="form.latitud" readonly />
                        <InputError class="w-full" :message="form.errors.latitud" />
                    </div>

                    <div>
                        <InputLabel value="Longitud" />
                        <TextInput class="w-full" v-model="form.longitud" readonly/>
                        <InputError class="w-full" :message="form.errors.longitud" />
                    </div>

                    <div class="sm:col-span-2">
                        <MapComponent @update:lat="updateLat" @update:lng="updateLng" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md mr-2" :disabled="form.processing">
                    {{ form.id == 0 ? "Registrar" : "Actualizar" }}
                </button>
            </div>
        </div>
    </form>
</template>
