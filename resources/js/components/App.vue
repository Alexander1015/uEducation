<template>
    <v-app id="body">
        <v-main>
            <!-- Navbar Superior -->
            <v-app-bar dense flat fixed class="bk_brown">
                <v-app-bar-nav-icon @click.prevent="nav = !nav" tile class="height_100">
                    <v-icon class="txt_white">menu</v-icon>
                </v-app-bar-nav-icon>
                <div class="d-none d-sm-flex">
                    <v-list-item class="bk_brown txt_white logo_top" @click.prevent="toInit" exact>
                        <v-img :src='logo.img' max-width="40" :lazy-src='logo.lazy'>
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
                            <v-btn text v-bind="attrs" v-on="on" class="height_100 bk_brown txt_white mx-0 pl-0 pr-2"
                                tile>
                                <v-list-item-avatar>
                                    <v-img :src='"/img/users/" + user.avatar' max-height="38" max-width="38"
                                        :lazy-src='"/img/users/" + user.avatar_lazy'>
                                        <template v-slot:placeholder>
                                            <v-row class="fill-height ma-0" align="center" justify="center">
                                                <v-progress-circular indeterminate color="grey lighten-5">
                                                </v-progress-circular>
                                            </v-row>
                                        </template>
                                    </v-img>
                                </v-list-item-avatar>
                                <span class="mx-1">{{ user.user }}</span>
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
                                            <v-icon>account_circle</v-icon>
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
            <v-navigation-drawer v-model="nav" temporary dark fixed dense flat class="bk_blue">
                <v-list>
                    <v-list-item>
                        <v-list-item-icon>
                            <v-icon>menu</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6">Menú</v-list-item-title>
                        </v-list-item-content>
                        <v-btn icon @click.prevent="nav = !nav">
                            <v-icon>arrow_back_ios_new</v-icon>
                        </v-btn>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
                <v-list nav dense>
                    <div v-for="link in links" :key="links.title">
                        <template v-if="link.title">
                            <v-list-item v-if="link.visible" link :to="link.to" class="my-1">
                                <v-list-item-icon>
                                    <v-icon>{{ link.icon }}</v-icon>
                                </v-list-item-icon>
                                <v-list-item-title>{{ link.title }}</v-list-item-title>
                            </v-list-item>
                        </template>
                        <template v-else-if="link.header && link.visible">
                            <v-divider class="my-2"></v-divider>
                        </template>
                    </div>
                </v-list>
            </v-navigation-drawer>
            <!-- Contenido -->
            <v-container id="container" fluid class="mt-9 mb-10 px-0">
                <router-view></router-view>
            </v-container>
            <!-- Footer -->
            <v-footer padless fixed>
                <v-card class="flex bk_brown" flat tile>
                    <v-card-text class="pt-1 pb-0 text-center txt_white caption">
                        <strong>Edgard Alexander Barrera Flamenco</strong>
                        <template v-if="today !== 2022">
                            <span>(2022 - {{ today }})</span>
                        </template>
                        <template v-else>
                            <span> - 2022</span>
                        </template>
                    </v-card-text>
                    <v-card-text class="pt-0 pb-1 text-center txt_white caption">
                        (alexanderbarrera105@gmail.com)
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
            avatar_lazy: null,
        },
        logo: {
            img: "/img/logo/logo.png",
            lazy: "/img/logo/logo_lazy.png",
        },
        links: [
            // Public
            { type: 0, title: "Inicio", icon: "token", to: "/", visible: true },
            { type: 0, title: "Contenido", icon: "school", to: { name: "contents" }, visible: true },
            // Login
            { type: 1, title: "Iniciar Sesión", icon: "account_box", to: { name: "auth" }, visible: true },
            // Dashboard
            { type: 2, header: true, visible: false },
            { type: 2, title: "Materias", icon: "collections_bookmark", to: { name: "subjects" }, visible: false },
            { type: 2, title: "Etiquetas", icon: "local_offer", to: { name: "tags" }, visible: false },
            { type: 2, title: "Temas", icon: "library_books", to: { name: "topics" }, visible: false },
            { type: 2, header: true, visible: false },
            { type: 2, title: "Usuarios", icon: "people", to: { name: "users" }, visible: false },
        ],
        to: {
            profile: { name: "profile" },
        },
        nav: false,
    }),
    methods: {
        toInit() {
            if (this.$route.path != "/") this.$router.push("/");
        },
        logout() {
            this.axios.post('/api/logout')
                .then(response => {
                    window.location.href = "/auth"
                }).catch((error) => {
                    console.log(error);
                    this.$router.push({ name: "error" });
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
                        if (this.user.avatar == "") {
                            this.user.avatar = "blank.png";
                            this.user.avatar_lazy = "blank_lazy.png";
                        }
                        else {
                            this.user.avatar = this.user.avatar + "/index.png";
                            this.user.avatar_lazy = this.user.avatar + "/lazy.png";
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