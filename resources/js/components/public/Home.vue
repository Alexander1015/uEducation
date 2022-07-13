<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="ma-2">
            <v-carousel cycle interval="10000" height="400" hide-delimiter-background show-arrows-on-hover
                delimiter-icon="fiber_manual_record" prev-icon="chevron_left" next-icon="chevron_right">
                <v-carousel-item v-for="(item, i) in items" :key="i" :src="item.src" :lazy-src="item.lazy_src">
                </v-carousel-item>
            </v-carousel>
        </div>
    </v-main>
</template>

<script>
export default {
    name: 'HomePublic',
    data: () => ({
        overlay: false,
        items: [
            {
                src: '/img/carousel/welcome.png',
                lazy_src: '/img/lazy_carousel/welcome.png',
            },
            {
                src: '/img/carousel/welcome.png',
                lazy_src: '/img/lazy_carousel/welcome.png',
            },
        ],
    }),
}
</script>