<script setup>
import { onMounted, ref, nextTick } from "vue";
import L from "leaflet";
import "leaflet/dist/leaflet.css";

const props = defineProps({
    initialLat: {
        type: Number,
        default: -6.7011, // Latitud predeterminada para Lambayeque
    },
    initialLng: {
        type: Number,
        default: -79.9061, // Longitud predeterminada para Lambayeque
    },
    height: {
        type: String,
        default: "200px", // Altura predeterminada del mapa
    },
});

const emit = defineEmits(["update:lat", "update:lng"]);

const mapContainer = ref(null);
const mapHeight = ref(props.height);

onMounted(async () => {
    await nextTick(); // Asegúrate de que el DOM esté completamente renderizado

    const map = L.map(mapContainer.value).setView(
        [props.initialLat, props.initialLng],
        13
    );
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const marker = L.marker([props.initialLat, props.initialLng], {
        draggable: true,
    }).addTo(map);

    marker.on("dragend", () => {
        const { lat, lng } = marker.getLatLng();
        emit("update:lat", lat);
        emit("update:lng", lng);
    });

    // Forzar la actualización del tamaño del mapa
    map.invalidateSize();
});
</script>

<template>
    <div ref="mapContainer" :style="{ height: mapHeight }"></div>
</template>
