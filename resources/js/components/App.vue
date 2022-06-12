<template>
    <v-app id="body">
        <v-main>
            <!-- Navbar Superior -->
            <v-app-bar dense flat fixed class="bk_brown">
                <v-app-bar-nav-icon @click.stop="nav = !nav">
                    <v-icon class="txt_white">menu</v-icon>
                </v-app-bar-nav-icon>
                <div class="d-none d-sm-flex">
                    <v-list-item class="bk_brown txt_white logo_top" @click.prevent="toInit" exact>
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
                                <template v-if="user.avatar">
                                    <v-list-item-avatar>
                                        <v-img :src='"/img/users/" + user.avatar' :max-height='logo.height'
                                            :lazy-src='"/img/lazy_users/" + user.avatar'>
                                            <template v-slot:placeholder>
                                                <v-row class="fill-height ma-0" align="center" justify="center">
                                                    <v-progress-circular indeterminate color="grey lighten-5">
                                                    </v-progress-circular>
                                                </v-row>
                                            </template>
                                        </v-img>
                                    </v-list-item-avatar>
                                </template>
                                <template v-else>
                                    <v-list-item-avatar>
                                        <v-img src="/img/users/blank.png" :max-height='logo.height'
                                            lazy-src="/img/lazy_users/blank.png">
                                            <template v-slot:placeholder>
                                                <v-row class="fill-height ma-0" align="center" justify="center">
                                                    <v-progress-circular indeterminate color="grey lighten-5">
                                                    </v-progress-circular>
                                                </v-row>
                                            </template>
                                        </v-img>
                                    </v-list-item-avatar>
                                </template>
                                <span class="ml-2 mr-1">{{ user.user }}</span>
                                <v-icon>keyboard_arrow_down</v-icon>
                            </v-btn>
                        </template>
                        <v-list>
                            <div>
                                <v-btn text :to="to.profile" class="width_100">
                                    <v-row>
                                        <v-col cols="8" class="text-center mt-1">
                                            <span>Perfil</span>
                                        </v-col>
                                        <v-col cols="4" class="text-center">
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
                        <template v-if="link.title">
                            <v-list-item v-if="link.visible" link :to="link.to" class="mb-2">
                                <v-list-item-icon>
                                    <v-icon>{{ link.icon }}</v-icon>
                                </v-list-item-icon>
                                <v-list-item-title>{{ link.title }}</v-list-item-title>
                            </v-list-item>
                        </template>
                        <template v-else-if="link.header">
                            <v-subheader v-if="link.visible" class="mb-2">
                                {{ link.header }}
                            </v-subheader>
                        </template>
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
                        <strong>uEducation</strong>
                        <template v-if="today !== 2022">
                            <span>(2022 - {{ today }})</span>
                        </template>
                        <template v-else>
                            <span> - 2022</span>
                        </template>
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
        today: new Date().getFullYear(),
        user: {
            user: "",
            avatar: null,
        },
        logo: {
            img: "/img/logo/logo.png",
            lazy: "/img/lazy/logo.png",
            width: "40",
            height: "40",
        },
        links: [
            // Public
            { type: 0, title: "Inicio", icon: "token", to: "/", visible: true },
            // Login
            { type: 1, title: "Iniciar Sesión", icon: "rocket_launch", to: { name: "auth" }, visible: true },
            // Dashboard
            { type: 2, header: "Dashboard", visible: false },
            { type: 2, title: "Cursos", icon: "collections_bookmark", to: { name: "subjects" }, visible: false },
            { type: 2, title: "Etiquetas", icon: "local_offer", to: { name: "tags" }, visible: false },
            { type: 2, title: "Temas", icon: "library_books", to: { name: "topics" }, visible: false },
            { type: 2, header: "Usuarios", visible: false },
            { type: 2, title: "Usuarios", icon: "people", to: { name: "users" }, visible: false },
        ],
        to: {
            profile: { name: "profile" },
        },
        nav: false,
    }),
    methods: {
        toInit() {
            this.$router.push("/");
        },
        logout() {
            this.axios.post('/api/logout')
                .then(response => {
                    window.location.href = "/auth"
                }).catch((error) => {
                    console.log(error);
                });
        },
        async login() {
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                    if (this.user.user) {
                        for (let link of this.links) {
                            if (link.type == 1) link.visible = false;
                            else if (link.type == 2) link.visible = true;
                        }
                    }
                }).catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.login()
    },
    watch: {
        group() {
            this.nav = false
        }
    }
}
</script>