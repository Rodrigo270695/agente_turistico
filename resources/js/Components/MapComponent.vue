<template>
    <div ref="mapContainer" :style="{ height: mapHeight, zIndex: 1 }"></div>
</template>

<script setup>
import { onMounted, ref, nextTick, watch } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    initialLat: {
        type: Number,
        default: null,
    },
    initialLng: {
        type: Number,
        default: null,
    },
    height: {
        type: String,
        default: "240px",
    },
    draggable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(["update:lat", "update:lng", "update:duration", "update:distance"]);

const mapContainer = ref(null);
const mapHeight = ref(props.height);
let map = null;
let marker = null;
let routeLayer = null;

const fixedLat = -6.770983493921217;
const fixedLng = -79.83925580978395;

const initializeMap = async () => {
    map = L.map(mapContainer.value).setView([fixedLat, fixedLng], 13);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const initialMarker = L.marker([fixedLat, fixedLng]).addTo(map);

    marker = L.marker([props.initialLat || fixedLat, props.initialLng || fixedLng], {
        draggable: props.draggable,
    }).addTo(map);

    if (props.draggable) {
        marker.on("dragend", async () => {
            const { lat, lng } = marker.getLatLng();
            emit("update:lat", lat);
            emit("update:lng", lng);
            await drawRoute(initialMarker.getLatLng(), { lat, lng });
        });
    }

    if (props.initialLat !== null && props.initialLng !== null) {
        await drawRoute(initialMarker.getLatLng(), { lat: props.initialLat, lng: props.initialLng });
    }

    map.invalidateSize();
};

const drawRoute = async (start, end) => {
    const response = await fetch(`https://router.project-osrm.org/route/v1/driving/${start.lng},${start.lat};${end.lng},${end.lat}?overview=full&geometries=geojson`);
    const data = await response.json();
    const route = data.routes[0].geometry;
    const duration = data.routes[0].duration;
    const distance = data.routes[0].distance;

    emit("update:duration", duration);
    emit("update:distance", distance);

    const hours = Math.floor(duration / 3600);
    const minutes = Math.floor((duration % 3600) / 60);
    const distanceKm = (distance / 1000).toFixed(2);

    const popupContent = `
        <b>Tiempo de viaje:</b> ${hours} horas y ${minutes} minutos<br>
        <b>Distancia:</b> ${distanceKm} km
    `;

    if (routeLayer) {
        map.removeLayer(routeLayer);
    }
    routeLayer = L.geoJSON(route, {
        style: { color: 'blue' }
    }).addTo(map);

    L.popup()
        .setLatLng([end.lat, end.lng])
        .setContent(popupContent)
        .openOn(map);
};

onMounted(async () => {
    await nextTick();
    await initializeMap();
});

watch([() => props.initialLat, () => props.initialLng], () => {
    if (map && marker) {
        map.setView([props.initialLat || fixedLat, props.initialLng || fixedLng], 13);
        marker.setLatLng([props.initialLat || fixedLat, props.initialLng || fixedLng]);
    }
});
</script>

<style scoped>
/* Asegúrate de que el mapa tenga un z-index menor que el modal */
div[ref="mapContainer"] {
    z-index: 1;
}
</style>
