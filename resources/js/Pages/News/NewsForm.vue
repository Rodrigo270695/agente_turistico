<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import { ref, defineProps, watch, onMounted } from "vue";

const props = defineProps({
    news: Object,
    departments: Array,
});

const selectedDepartment = ref("LAMBAYEQUE");
const selectedProvince = ref(null);
const provinces = ref([]);
const districts = ref([]);
const selectedDistrict = ref(null);

const form = useForm({
    _method: props.news ? 'PUT' : 'POST',
    id: props.news ? String(props.news.id) : "",
    title: props.news ? props.news.title : "",
    content: props.news ? props.news.content : "",
    date_end: props.news ? props.news.date_end : "",
    image: null,
    district_id: props.news ? String(props.news.district_id) : "",
});

onMounted(() => {
    if (props.news && props.news.district) {
        const department = props.departments.find(d => d.id === props.news.district.province.department_id);
        if (department) {
            selectedDepartment.value = department.name;
            provinces.value = department.provinces;
            const province = department.provinces.find(p => p.id === props.news.district.province_id);
            if (province) {
                selectedProvince.value = province.name;
                districts.value = province.districts;
                const district = districts.value.find(d => d.id === props.news.district_id);
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

watch(selectedProvince, (newValue) => {
    if (newValue) {
        const department = props.departments.find(d => d.name === selectedDepartment.value);
        if (department) {
            const province = department.provinces.find(p => p.name === newValue);
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

const submit = () => {
    if (props.news) {
        form.post(route("noticias.update", props.news.id), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    } else {
        form.post(route("noticias.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const toTitleCase = (str) => {
    return str.replace(/\w\S*/g, (txt) => {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
};

const handleFileChange = (event) => {
    form.image = event.target.files[0];
};

const emit = defineEmits(["close-modal"]);

</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg sm:text-xl text-slate-800 font-bold mb-4 border-b-2">
                {{ form.id == 0 ? "Registrar Noticia" : "Actualizar Noticia" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <div class="flex">
                            <InputLabel value="Provincia" />
                            <span class="text-red-500 text-lg">*</span>
                        </div>
                        <select v-model="selectedProvince" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" >
                            <option disabled selected value="">Seleccione una opción</option>
                            <option v-for="province in provinces" :key="province.id" :value="province.name">{{ province.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.province" />
                    </div>

                    <div>
                        <div class="flex">
                            <InputLabel value="Distrito" />
                            <span class="text-red-500 text-lg">*</span>
                        </div>
                        <select v-model="form.district_id" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option disabled selected value="">Seleccione una opción</option>
                            <option v-for="district in districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                        </select>
                        <InputError class="w-full" :message="form.errors.district_id" />
                    </div>

                    <div>
                        <div class="flex">
                            <InputLabel value="Título" />
                            <span class="text-red-500 text-lg">*</span>
                        </div>
                        <TextInput class="w-full" v-model="form.title" @input="form.title = toTitleCase(form.title)" />
                        <InputError class="w-full" :message="form.errors.title" />
                    </div>

                    <div>
                        <div class="flex">
                            <InputLabel value="Fecha Fin" />
                            <span class="text-red-500 text-lg">*</span>
                        </div>
                        <TextInput type="date" class="w-full" v-model="form.date_end" />
                        <InputError class="w-full" :message="form.errors.date_end" />
                    </div>

                    <div class="col-span-2">
                        <div class="flex">
                            <InputLabel value="Contenido" />
                            <span class="text-red-500 text-lg">*</span>
                        </div>
                        <TextArea class="w-full" v-model="form.content" />
                        <InputError class="w-full" :message="form.errors.content" />
                    </div>

                    <div class="col-span-2">
                        <div class="flex">
                            <InputLabel value="Imagen/Video" />
                            <span class="text-red-500 text-lg">*</span>
                        </div>
                        <input type="file" @change="handleFileChange" class="w-full bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" accept="image/*" />
                        <InputError class="w-full" :message="form.errors.image" />
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
