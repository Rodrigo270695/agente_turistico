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
});

const selection = ref("no");
const selectedDepartment = ref(null);
const selectedProvince = ref(null);
const provinces = ref([]);
const districts = ref([]);
const selectedDistrict = ref(null);

const form = useForm({
    id: props.place ? props.place.id : "",
    name: props.place ? props.place.name : "",
    department: props.place ? props.place.department : "",
    province: props.place ? props.place.province : "",
    district: props.place ? props.place.district : "",
    description: props.place ? props.place.description : "",
    address: props.place ? props.place.address : "",
    type_place: props.place ? props.place.type_place : "",
    care_day: props.place ? props.place.care_day : "",
    opening_hours: props.place ? props.place.opening_hours : "",
    price: props.place ? props.place.price : "",
    access: props.place ? props.place.access : "",
});

watch(() => props.place, (newPlace) => {
    if (newPlace) {
        selectedDepartment.value = newPlace.department;
        const department = props.departments.find(d => d.name === selectedDepartment.value);
        if (department) {
            provinces.value = department.provinces;
            selectedProvince.value = newPlace.province;
            const province = department.provinces.find(p => p.name === selectedProvince.value);
            if (province) {
                districts.value = province.districts;
                selectedDistrict.value = newPlace.district;
            }
        }
        form.id = newPlace.id;
        form.name = newPlace.name;
    }
}, { immediate: true });



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
        form.put(route('places.update', props.place.id), {
            preserveScroll: true,
            onSuccess: () => emit('close-modal')
        });
    } else {
        form.post(route("places.store"), {
            preserveScroll: true,
            onSuccess: () => emit("close-modal"),
        });
    }
};

const emit = defineEmits(["close-modal"]);

const datos = [
    { id: 1, name: "A" },
    { id: 2, name: "B" },
    { id: 3, name: "C" },
];

const access = [
    { id: 1, name: "A" },
    { id: 2, name: "B" },
    { id: 3, name: "C" },
];
</script>

<template>
    <form @submit.prevent="submit">
        <div class="bg-white shadow-md rounded-md p-4">
            <div class="text-lg font-bold mb-4">
                {{ form.id == 0 ? "Registrar Lugar" : "Actualizar Lugar" }}
            </div>
            <div class="mb-4">
                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Nombre" />
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

                    <TextInput
                        type="hidden"
                        class="w-full"
                        v-model="form.department"
                        :value="selectedDepartment"
                    />

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
                    <TextInput
                        type="hidden"
                        class="w-full"
                        v-model="form.province"
                        :value="selectedProvince"
                    />

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Distrito" />
                        <select
                            id="select"
                            v-model="form.district"
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
                            :message="form.errors.district"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Descripción" />
                        <TextArea class="w-full" v-model="form.description" />
                        <InputError
                            class="w-full"
                            :message="form.errors.description"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Dirección" />
                        <TextInput class="w-full" v-model="form.address" />
                        <InputError
                            class="w-full"
                            :message="form.errors.address"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Tipo de lugar" />
                        <select
                            id="select"
                            v-model="form.type_place"
                            class="bg-gray-50 border border-blue-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 white:bg-gray-700 white:border-gray-600 white:placeholder-gray-400 white:text-white white:focus:ring-blue-500 white:focus:border-blue-500"
                        >
                            <option disabled selected value="">
                                Seleccione un opción
                            </option>
                            <option
                                v-for="dato in datos"
                                :key="dato.id"
                                :value="dato.name"
                            >
                                {{ dato.name }}
                            </option>
                        </select>
                        <InputError
                            class="w-full"
                            :message="form.errors.type_place"
                        />
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Días de atención" />
                        <TextInput class="w-full" v-model="form.care_day" />
                        <InputError
                            class="w-full"
                            :message="form.errors.care_day"
                        />
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

                    <div class="col-span-6 sm:col-span-3">
                        <InputLabel value="Acceso" />
                        <select
                            id="select"
                            v-model="form.access"
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
                            :message="form.errors.access"
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
