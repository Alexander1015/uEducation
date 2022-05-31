<template>
    <v-app id="body">
        <v-main>
            <!-- Navbar Superior -->
            <v-app-bar dense flat fixed class="bk_brown">
                <v-app-bar-nav-icon @click.stop="nav = !nav">
                    <v-icon class="txt_white">menu</v-icon>
                </v-app-bar-nav-icon>
                <div class="logo">
                    <v-list-item class="bk_brown txt_white" to="/" exact>
                        <v-img :src='logo.img' :max-width='logo.width' :lazy-src='logo.lazy'>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                        <v-list-item-content class="pl-6">
                            <v-list-item-title class="text-h6">uEducation</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </div>
                <template v-if="user.user">
                    <v-spacer></v-spacer>
                    <v-menu transition="slide-y-transition" offset-y>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn text v-bind="attrs" v-on="on" class="height_100 bk_brown txt_white" tile>
                                <v-icon>account_circle</v-icon>
                                <span class="ml-2 mr-1">{{ user.user }}</span>
                                <v-icon>keyboard_arrow_down</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <div>
                                <v-btn text :to="to.profile" class="width_100">
                                    <v-row>
                                        <v-col cols="8" class="mx-auto mt-1">
                                            <span>Perfil</span>
                                        </v-col>
                                        <v-col cols="4" class="mx-auto">
                                            <v-icon>manage_accounts</v-icon>
                                        </v-col>
                                    </v-row>
                                </v-btn>
                            </div>
                            <div>
                                <v-btn text @click.prevent="logout" class="width_100">
                                    <v-row>
                                        <v-col cols="8" class="mx-auto mt-1">
                                            <span>Salir</span>
                                        </v-col>
                                        <v-col cols="4" class="mx-auto">
                                            <v-icon>logout</v-icon>
                                        </v-col>
                                    </v-row>
                                </v-btn>
                            </div>
                        </v-list>
                    </v-menu>
                </template>
            </v-app-bar>
            <!-- Navbar Lateral -->
            <v-navigation-drawer v-model="nav" temporary dark fixed class="bk_blue">
                <v-list>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>menu</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6">Menú</v-list-item-title>
                        </v-list-item-content>
                        <v-btn icon @click.stop="nav = !nav">
                            <v-icon>arrow_back_ios_new</v-icon>
                        </v-btn>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list nav dense>
                    <div v-for="link in links" :key="links.title">
                        <v-list-item v-if="link.visible" link :to="link.to" class="mb-2">
                            <v-list-item-icon>
                                <v-icon>{{ link.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title>{{ link.title }}</v-list-item-title>
                        </v-list-item>
                    </div>
                </v-list>
            </v-navigation-drawer>
            <!-- Contenido -->
            <v-container id="container" fluid class="mt-9 mb-11 px-0">
                <router-view></router-view>
            </v-container>
            <!-- Footer -->
            <v-footer padless absolute>
                <v-card class="flex bk_brown" flat tile>
                    <v-card-text class="py-2 text-center txt_white">
                        <strong>uEducation</strong> - {{ new Date().getFullYear() }}
                    </v-card-text>
                </v-card>
            </v-footer>
        </v-main>
    </v-app>
</template>

<script>
export default {
    name: "App",
    data: () => ({
        user: {
            user: "",
        },
        logo: {
            img: "/img/logo/logo.png",
            lazy: "/img/lazy/logo.png",
            width: "40",
        },
        links: [
            { title: "Inicio", icon: "token", to: "/", visible: true },
            { title: "Cursos/Materias", icon: "collections_bookmark", to: "/cursos", visible: true },
            { title: "Iniciar Sesión", icon: "rocket_launch", to: { name: "auth" }, visible: true },
            { title: "Dashboard", icon: "dashboard", to: { name: "dashboard" }, visible: false },
        ],
        to: {
            perfil: { name: "profile" },
        },
        nav: false,
    }),
    methods: {
        logout() {
            this.axios.post('/api/logout')
                .then(response => {
                    window.location.href = "auth"
                }).catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.axios.get('/api/auth')
            .then(response => {
                this.user = response.data;
                if (this.user.user) {
                    this.links[2].visible = false;
                    this.links[3].visible = true;
                }
            }).catch((error) => {
                console.log(error);
            });
    },
    watch: {
        group() {
            this.nav = false
        }
    }
}
</script>