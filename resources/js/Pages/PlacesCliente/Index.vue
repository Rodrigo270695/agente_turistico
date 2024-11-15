<script setup>
import { Head, router, Link } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Layout from "../Layout.vue";
import Pagination from "@/Components/Pagination.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, defineProps, watch, onMounted } from "vue";

const props = defineProps({
    places: Object,
    departments: Array,
    categories: Array,
    searchParams: Object,
});

const selectedDepartment = ref("LAMBAYEQUE");
const selectedProvince = ref(null);
const provinces = ref([]);
const districts = ref([]);
const selectedDistrict = ref(null);
const selectedCategory = ref(null);
const typeCategories = ref([]);
const selectedTypeCategory = ref(null);
const searchParams = ref({
    nombre: props.searchParams?.nombre || "",
    provincia: props.searchParams?.provincia || "",
    distrito: props.searchParams?.distrito || "",
    categoria: props.searchParams?.categoria || "",
    tipo: props.searchParams?.tipo || "",
    distancia: props.searchParams?.distancia || "",
});

onMounted(() => {
    selectedDepartment.value = "LAMBAYEQUE";
    const initialDepartment = props.departments.find(
        (d) => d.name === selectedDepartment.value
    );
    if (initialDepartment) {
        provinces.value = initialDepartment.provinces;
    }
});

watch(selectedDepartment, (newValue) => {
    if (newValue) {
        const selectedDepartment = props.departments.find(
            (department) => department.name === newValue
        );
        if (selectedDepartment) {
            provinces.value = selectedDepartment.provinces;
        } else {
            provinces.value = [];
        }
        selectedProvince.value = null;
        districts.value = [];
    } else {
        provinces.value = [];
        selectedProvince.value = null;
        districts.value = [];
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
            } else {
                districts.value = [];
            }
        }
    } else {
        districts.value = [];
    }
});

watch(selectedCategory, (newValue) => {
    if (newValue) {
        const category = props.categories.find(
            (category) => category.nombre === newValue
        );
        if (category) {
            typeCategories.value = category.typecategories;
        } else {
            typeCategories.value = [];
        }
        selectedTypeCategory.value = null;
    } else {
        typeCategories.value = [];
        selectedTypeCategory.value = null;
    }
});

const search = () => {
    searchParams.value = {
        nombre: searchParams.value.nombre,
        provincia: selectedProvince.value,
        distrito: selectedDistrict.value,
        categoria: selectedCategory.value,
        tipo: selectedTypeCategory.value,
        distancia: searchParams.value.distancia,
    };
    router.get(route("placesclient.search"), searchParams.value, {
        preserveState: true,
    });
};

const form = useForm({
    place: Object,
});

const goToIndex = () => {
    form.get(route("places"));
};

</script>

<template>
    <Layout>
        <Head title="Lugares" />

        <div
            class="relative flex justify-center items-center py-4 bg-cover bg-center"
            style="background-image: url('/path/to/background-image.jpg')"
        >
            <div class="absolute inset-0 bg-[#e8a860] opacity-75"></div>
            <div
                class="relative z-10 text-center text-white px-1 sm:px-12"
            >
                <h1 class="text-4xl font-bold mb-4">
                    Encuentra la visita perfecta
                </h1>
                <div class="bg-white p-6 rounded-md shadow-md text-gray-900">
                    <form
                        @submit.prevent="search"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4"
                    >
                        <div>
                            <InputLabel class="text-left" value="Nombre" />
                            <TextInput
                                v-model="searchParams.nombre"
                                class="w-full"
                            />
                        </div>
                        <div>
                            <InputLabel class="text-left" value="Provincia" />
                            <select
                                v-model="selectedProvince"
                                class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                            >
                                <option disabled selected value="">
                                    Seleccione una opción
                                </option>
                                <option
                                    v-for="province in provinces"
                                    :key="province.id"
                                    :value="province.name"
                                >
                                    {{ province.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <InputLabel class="text-left" value="Distrito" />
                            <select
                                v-model="selectedDistrict"
                                :disabled="!selectedProvince"
                                class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                            >
                                <option disabled selected value="">
                                    Seleccione una opción
                                </option>
                                <option
                                    v-for="district in districts"
                                    :key="district.id"
                                    :value="district.name"
                                >
                                    {{ district.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <InputLabel class="text-left" value="Categoría" />
                            <select
                                v-model="selectedCategory"
                                class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                            >
                                <option disabled selected value="">
                                    Seleccione una opción
                                </option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.nombre"
                                >
                                    {{ category.nombre }}
                                </option>
                            </select>
                        </div>
                        <div >
                            <InputLabel class="text-left" value="Tipo" />

                            <select
                                v-model="selectedTypeCategory"
                                :disabled="!selectedCategory"
                                class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                            >
                                <option disabled selected value="">
                                    Seleccione una opción
                                </option>
                                <option
                                    v-for="typeCategory in typeCategories"
                                    :key="typeCategory.id"
                                    :value="typeCategory.nombre"
                                >
                                    {{ typeCategory.nombre }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                for="distancia"
                                class="block text-sm font-medium text-black text-left"
                                >Distancia</label
                            >
                            <select
                                v-model="searchParams.distancia"
                                id="distancia"
                                class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"
                            >
                                <option value="">
                                    Seleccione una distancia
                                </option>
                                <option value="menos de 30 min">
                                    Menos de 30 min
                                </option>
                                <option value="30 min a 1 hora">
                                    30 min a 1 hora
                                </option>
                                <option value="más de 1 hora">
                                    Más de 1 hora
                                </option>
                            </select>
                        </div>
                        <div
                            class="col-span-1 md:col-span-2 lg:col-span-4 xl:col-span-6"
                        >
                            <button
                                type="submit"
                                class="w-full bg-[#64161d] text-white py-2 rounded-md hover:bg-[#e8a860]"
                            >
                                Buscar
                            </button>
                        </div>
                    </form>
                    <div class="inline-block mt-5">
                        <button @click="goToIndex" class="bg-[#e8a860] text-white py-2 px-4 rounded-md hover:bg-[#64161d]">Limpiar busqueda</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tourist Centers Section -->
        <div class="mx-4 md:mx-28 py-12">
            <h2 class="text-2xl font-bold text-center mb-8 text-[#64161d]">
                Centros Turísticos
            </h2>
            <!-- Leyenda de Llegada -->
            <div class="mt-8 p-4 bg-gradient-to-r from-blue-100 to-blue-200 rounded-md shadow-sm">
                <h3 class="text-md font-semibold mb-2 text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12.9A9 9 0 1112.9 3 9 9 0 0121 12.9z"></path>
                    </svg>
                    Leyenda de Llegada
                </h3>
                <ul class="list-none pl-0 text-gray-700 space-y-1">
                    <li class="flex items-center">
                        <span class="bg-green-200 text-green-800 rounded-full px-2 py-0.5 mr-2 text-sm">TOTAL</span>
                        <span class="text-sm">Acceso completo sin restricciones.</span>
                    </li>
                    <li class="flex items-center">
                        <span class="bg-yellow-200 text-yellow-800 rounded-full px-2 py-0.5 mr-2 text-sm">PARCIAL</span>
                        <span class="text-sm">Acceso restringido; puede requerir caminar un poco más.</span>
                    </li>
                </ul>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
        v-for="place in places.data"
        :key="place.id"
        class="bg-white rounded-lg shadow-lg overflow-hidden"
    >
        <img
            :src="
                place.photos && place.photos.length
                    ? place.photos[0].url
                    : '/path/to/default-image.jpg'
            "
            :alt="place.nombre"
            class="w-full h-48 object-cover"
        />
        <div class="p-6">
            <h3 class="text-2xl font-bold text-[#64161d] mb-2">
                {{ place.nombre }}
            </h3>
            <p class="text-gray-600 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                </svg>
                <strong>Ubicación: </strong>
                {{ place.district.province.name }} /
                {{ place.district.name }}
            </p>
            <p class="text-gray-600 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13z"/>
                </svg>
                <strong>Época de visita:</strong>
                {{ place.epoca_visita }}
            </p>
            <p class="text-gray-600 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-[#64161d]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11h-8V4H4v16h16V11zm-2 9H6V6h6v5h6v9z"/>
                </svg>
                <strong>Días abierto:</strong>
                {{ place.dias_abierto_desde }} -
                {{ place.dias_cerrado_hasta }}
            </p>
            <p class="text-gray-600 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M3 21l18-18" />
                </svg>
                <strong>Distancia: </strong>
                {{ place.distancia_horas }} horas
            </p>
            <template v-if="place.prices.length > 0">
                <p class="text-gray-600 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <strong>Precio: </strong>
                    <div class="flex space-x-4">
                        <template v-for="price in place.prices" :key="price.id">
                            <span v-if="price.tipo_persona === 'Adulto'">
                                Adulto: S/. {{ price.precio }}
                            </span>
                            <span v-if="price.tipo_persona === 'Estudiante'">
                                Estudiante: S/. {{ price.precio }}
                            </span>
                            <span v-if="price.tipo_persona === 'Preferencial'">
                                Preferencial: S/. {{ price.precio }}
                            </span>
                        </template>
                    </div>
                </p>
            </template>
            <template v-else>
                <p class="text-gray-600 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 text-[#64161d]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <strong>Precio: </strong>Gratis
                </p>
            </template>
            <p class="text-gray-600 mb-4">
                <strong>LLegada: </strong> {{ place.tipo_acceso }}
                <v-icon
                    v-if="place.tipo_acceso == 'PARCIAL'"
                    class="text-[#64161d]"
                    name="ri-footprint-fill"
                />
                <v-icon
                    class="text-[#64161d]"
                    name="fa-car-side"
                />
            </p>
            <Link
                :href="route('placesclient.show', place.id)"
                class="inline-block bg-[#64161d] text-white py-2 px-4 rounded-md hover:bg-[#e8a860]"
                >Ver Detalles</Link
            >
        </div>
    </div>
            </div>
            <Pagination class="mt-6" :pagination="places" />
        </div>
    </Layout>
</template>

<style>
/* Estilos adicionales para mejorar la apariencia */
body {
    font-family: "Inter", sans-serif;
}

input,
select,
button {
    transition: all 0.3s ease;
}

input:focus,
select:focus,
button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(232, 168, 96, 0.5);
}

button:hover {
    background-color: #e8a860;
}
</style>
