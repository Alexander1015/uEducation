<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="ma-2">
            <template v-if="user.user">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="mx-2 bk_green txt_white btn_carousel" fab v-bind="attrs" v-on="on"
                            @click.stop="gotoCarousel()">
                            <v-icon>
                                app_registration
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Configurar</span>
                </v-tooltip>
            </template>
            <v-carousel cycle interval="6000" height="500" hide-delimiter-background show-arrows-on-hover
                delimiter-icon="fiber_manual_record" prev-icon="chevron_left" next-icon="chevron_right">
                <v-carousel-item src="/img/carousel/welcome.png" lazy-src="/img/lazy_carousel/welcome.png">
                </v-carousel-item>
                <v-carousel-item v-for="(item, i) in items" :key="i" :src='"/img/carousel/" + item.img'
                    :lazy-src='"/img/lazy_carousel/" + item.img'>
                </v-carousel-item>
            </v-carousel>
            <div class="mt-8 mx-4">
                <v-row>
                    <v-col cols="12" sm="4" class="mt-2 mx-auto bk_blue rounded-lg">
                        <v-img max-height="200" src="/img/logo/logo-w-letters.png"
                            lazy-src="/img/lazy/logo-w-letters.png" contain>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5">
                                    </v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </v-col>
                    <v-col cols="12" sm="4" class="mt-2 mx-auto bk_brown txt_white rounded-lg">
                        <p class="text-justify pa-4">
                            uEducation es una aplicacion web que le permitira a los docentes el crear blogs de
                            diferentes temas, de acuerdo a las materias que se encuentren en la misma, permitiendole al
                            estudiante, poder leer cada blog que ha sido creada.
                        </p>
                    </v-col>
                </v-row>
            </div>
        </div>
    </v-main>
</template>

<script>
export default {
    name: 'HomePublic',
    data: () => ({
        overlay: false,
        items: [],
        user: {
            id: "",
            user: "",
        },
    }),
    mounted() {
        this.init();
    },
    methods: {
        gotoCarousel() {
            this.overlay = true;
            this.$router.push({ name: "carousel" });
        },
        async init() {
            this.overlay = true;
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                });
            await this.axios.get('/api/getcarousel/')
                .then(response => {
                    this.items = response.data;
                    this.overlay = false;
                }).catch((error) => {
                    console.log(error);
                    this.overlay = false;
                });
        },
    },
}
</script>