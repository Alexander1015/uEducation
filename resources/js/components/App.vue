<template>
    <v-app id="body">
        <v-main>
            <!-- Navbar Superior -->
            <v-app-bar dense flat fixed class="bk_brown">
                <v-app-bar-nav-icon @click.stop="nav = !nav">
                    <v-icon class="txt_white">menu</v-icon>
                </v-app-bar-nav-icon>
                <div>
                    <v-list-item class="bk_brown txt_white">
                        <v-img :src='logo.img' :max-width='logo.width'></v-img>
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
            <v-container id="container" fluid class="mt-11 mb-10">
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
            img: "./img/logo/logo.png",
            width: "40",
        },
        links: [
            { title: "Inicio", icon: "token", to: "/" },
            { title: "Cursos/Materias", icon: "collections_bookmark", to: "/cursos" },
            { title: "Iniciar Sesión", icon: "rocket_launch", to: "/auth" },
            { title: "Registrarse", icon: "data_saver_on", to: "/register" },
            { title: "Dashboard", icon: "dashboard", to: "/dashboard" },
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