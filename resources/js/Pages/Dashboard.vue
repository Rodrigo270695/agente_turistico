<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import VueApexCharts from 'vue3-apexcharts';
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const mostVisitedPlaces = ref(props.mostVisitedPlaces || []);
const currentMonthVisits = ref(props.currentMonthVisits || []);

// Configuración del gráfico
const chartOptions = {
    chart: {
        type: 'bar'
    },
    series: [{
        name: 'Visitas',
        data: mostVisitedPlaces.value.map(place => place.visit_count)
    }],
    xaxis: {
        categories: mostVisitedPlaces.value.map(place => place.nombre)
    }
};

// Configuración del gráfico de líneas
const lineChartOptions = {
    chart: {
        type: 'line'
    },
    series: [{
        name: 'Visitas Diarias',
        data: currentMonthVisits.value.map(visit => visit.visit_count)
    }],
    xaxis: {
        categories: currentMonthVisits.value.map(visit => visit.day)
    }
};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-36 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="grid grid-cols-2 gap-4 p-5">
                        <div class="shadow-xl p-4 rounded-md">
                            <h3 class="text-lg font-medium text-gray-700 mb-4 text-center ">
                                Lugares Más Visitados
                            </h3>
                            <vue-apex-charts type="bar" :options="chartOptions" :series="chartOptions.series"></vue-apex-charts>
                        </div>
                        <div class="shadow-xl p-4 rounded-md">
                            <h3 class="text-lg font-medium text-gray-700 mb-4 text-center">
                                Visitas del Mes Actual
                            </h3>
                            <vue-apex-charts type="line" :options="lineChartOptions" :series="lineChartOptions.series"></vue-apex-charts>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
