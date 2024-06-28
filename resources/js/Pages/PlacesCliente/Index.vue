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
                        <div>
                            <InputLabel class="text-left" value="Tipo" />
                            <select
                                v-model="selectedTypeCategory"
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
                        <p class="text-gray-600 mb-2">
                            <strong>Ubicación: </strong>
                            {{ place.district.province.name }} /
                            {{ place.district.name }}
                        </p>
                        <p class="text-gray-600 mb-2">
                            <strong>Época de visita:</strong>
                            {{ place.epoca_visita }}
                        </p>
                        <p class="text-gray-600 mb-4">
                            <strong>Días abierto:</strong>
                            {{ place.dias_abierto_desde }} -
                            {{ place.dias_cerrado_hasta }}
                        </p>
                        <p class="text-gray-600 mb-4">
                            <strong>Distancia: </strong>
                            {{ place.distancia_horas }} horas
                        </p>
                        <p class="text-gray-600 mb-4">
                            <strong>LLegada: </strong> {{ place.tipo_acceso }}
                            <v-icon
                                class="text-[#64161d]"
                                name="ri-footprint-fill"
                            />
                            <v-icon
                                v-if="place.tipo_acceso == 'total'"
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
