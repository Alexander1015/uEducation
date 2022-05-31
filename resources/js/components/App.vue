<template>
    <v-app id="body">
        <v-main>
            <!-- Navbar Superior -->
            <v-app-bar dense flat fixed class="bk_brown">
                <v-app-bar-nav-icon @click.stop="nav = !nav">
                    <v-icon class="txt_white">menu</v-icon>
                </v-app-bar-nav-icon>
                <div>
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
                        <v-list-item link :to="link.to" class="mb-2">
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
        logo: {
            img: "/img/logo/logo.png",
            lazy: "/img/lazy/logo.png",
            width: "40",
        },
        links: [
            { title: "Inicio", icon: "token", to: "/" },
            { title: "Cursos/Materias", icon: "collections_bookmark", to: "/cursos" },
            { title: "Iniciar Sesión", icon: "rocket_launch", to: { name: "auth" } },
            { title: "Dashboard", icon: "dashboard", to: { name: "dashboard" } },
        ],
        nav: false,
    }),
    watch: {
        group() {
            this.nav = false
        }
    }
}
</script>