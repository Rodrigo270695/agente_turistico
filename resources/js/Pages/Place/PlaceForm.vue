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
    categories: Array,
});

const selection = ref("no");
const selectedDepartment = ref("LAMBAYEQUE");
const selectedProvince = ref(null);
const provinces = ref([]);
const districts = ref([]);
const selectedDistrict = ref(null);
const selectedCategory = ref(null);
const typeCategories = ref([]);
const selectedTypeCategory = ref(null); 
const subCategories = ref([]);

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
    distancia_horas: props.place ? props.place.distancia_horas : "",
    distancia_km: props.place ? props.place.distancia_km : "",
    epoca_visita: props.place ? props.place.epoca_visita : "",
    entrada: props.place ? props.place.entrada : "",
    tipo_acceso: props.place ? props.place.tipo_acceso : "",
    district_id: props.place ? props.place.district_id : '',
    sub_category_id: props.place ? props.place.sub_category_id : '',
});

onMounted(() => {
    console.log("props.place:", props.place);
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

    if (props.place && props.place.subcategory) {
        const category = props.categories.find(c => c.id === props.place.subcategory.typecategory.category.id);
        console.log("category:", category);
        if (category) {
            selectedCategory.value = category.id;
            typeCategories.value = category.typecategories;
            const typeCategory = category.typecategories.find(tc => tc.id === props.place.subcategory.type_category_id);
            console.log("typeCategory:", typeCategory);
            if (typeCategory) {
                selectedTypeCategory.value = typeCategory.id;
                subCategories.value = typeCategory.subcategories;
            }
        }
    }
});

watch(selectedCategory, (newValue) => {
    if (newValue) {
        const category = props.categories.find(c => c.id === newValue);
        if (category) {
            typeCategories.value = category.typecategories;
            form.category_id = newValue;
        } else {
            typeCategories.value = [];
            form.category_id = "";
        }
    } else {
        typeCategories.value = [];
        form.category_id = "";
    }
});

watch(selectedTypeCategory, (newValue) => {
    if (newValue) {
        const typeCategory = typeCategories.value.find(tc => tc.id === newValue);
        if (typeCategory) {
            subCategories.value = typeCategory.subcategories;
            form.type_category_id = newValue;
        } else {
            subCategories.value = [];
            form.type_category_id = "";
        }
    } else {
        subCategories.value = [];
        form.type_category_id = "";
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
            form.nombre = newPlace.nombre;
            form.latitud = newPlace.latitud;
            form.longitud = newPlace.longitud;
            form.distancia_horas = newPlace.distancia_horas;
            form.distancia_km = newPlace.distancia_km;
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
const entradas = [
    { id: 'P', name: "Pagado" },
    { id: 'L', name: "Libre" },
    { id: 'R', name: "Restringido" },
];

const days = [
    { id: 1, name: "Lunes" },
    { id: 2, name: "Martes" },
    { id: 3, name: "Miércoles" },
    { id: 4, name: "Jueves" },
    { id: 5, name: "Viernes" },
    { id: 6, name: "Sábado" },
    { id: 7, name: "Domingo" },
];

const updateLat = (newLat) => {
    form.latitud = newLat;
};

const updateLng = (newLng) => {
    form.longitud = newLng;
};

const updateDuration = (newDuration) => {
    const hours = Math.floor(newDuration / 3600);
    const minutes = Math.floor((newDuration % 3600) / 60);
    form.distancia_horas = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
};

const updateDistance = (newDistance) => {
    form.distancia_km = (newDistance / 1000).toFixed(2);
};

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2">
                {{ form.id == 0 ? "Registrar Lugar" : "Actualizar Lugar" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

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
                        <InputLabel value="Categoría" />
                        <select v-model="selectedCategory" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione una opción</option>
                            <option v-for="category in props.categories" :key="category.id" :value="category.id">{{ category.nombre }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.category_id" />
                    </div>

                    <div>
                        <InputLabel value="Tipo de Categoría" />
                        <select v-model="selectedTypeCategory" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione una opción</option>
                            <option v-for="typeCategory in typeCategories" :key="typeCategory.id" :value="typeCategory.id">{{ typeCategory.nombre }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.type_category_id" />
                    </div>

                    <div>
                        <InputLabel value="Subcategoría" />
                        <select v-model="form.sub_category_id" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione una opción</option>
                            <option v-for="subCategory in subCategories" :key="subCategory.id" :value="subCategory.id">{{ subCategory.nombre }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.sub_category_id" />
                    </div>

                    <div class="sm:col-span-2">
                        <InputLabel value="Dirección" />
                        <TextInput class="w-full" v-model="form.direccion" />
                        <InputError class="w-full" :message="form.errors.direccion" />
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel value="Distancia (Horas)" />
                        <TextInput class="w-full" v-model="form.distancia_horas" type="time" />
                        <InputError class="w-full" :message="form.errors.distancia_horas" />
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel value="Distancia (Km)" />
                        <TextInput class="w-full" v-model="form.distancia_km" />
                        <InputError class="w-full" :message="form.errors.distancia_km" />
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel value="Epoca de visita" />
                        <TextInput class="w-full" v-model="form.epoca_visita" />
                        <InputError class="w-full" :message="form.errors.epoca_visita" />
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel value="Entrada" />
                        <select v-model="form.entrada" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione un opción</option>
                            <option v-for="(ent,index) in entradas" :key="index" :value="ent.id">{{ ent.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.entrada" />
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

                    <div class="sm:col-span-3">

                        <MapComponent
                            :initialLat="props.place ? form.latitud : null"
                            :initialLng="props.place ? form.longitud : null"
                            @update:lat="updateLat"
                            @update:lng="updateLng"
                            @update:duration="updateDuration"
                            @update:distance="updateDistance"
                        />
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
