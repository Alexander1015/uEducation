<template>
    <v-app id="body">
        <v-main>
            <!-- Barra de Navegación -->
            <v-toolbar dense flat pad dark elevation="1" id="toolbar">
                <router-link to="/" class="logo">
                    <v-img :src="navbar.logo" :lazy-src="navbar.logo" :width="navbar.width_logo" contain
                        class="logo_navbar">
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                    <v-toolbar-title class="title_navbar">uEducation</v-toolbar-title>
                </router-link>

                <v-spacer></v-spacer>

                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn tile to="/" text class="btn-navbar" v-bind="attrs" v-on="on">
                            <v-icon>home</v-icon>
                        </v-btn>
                    </template>
                    <span>Inicio</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn tile to="/auth" text class="btn-navbar" v-bind="attrs" v-on="on">
                            <v-icon>account_circle</v-icon>
                        </v-btn>
                    </template>
                    <span>Ingresar</span>
                </v-tooltip>
            </v-toolbar>

            <!-- Contenido -->
            <v-container fluid>
                <router-view></router-view>
            </v-container>
            <!-- Fin de Contenido -->

            <!-- Footer -->
            <v-footer padless absolute>
                <v-card id="footer" class="flex" flat tile>
                    <v-card-text class="py-2 black--text text-center">
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
        navbar: {
            logo: "./img/logo1.png",
            width_logo: "50",
        },
    }),
}
</script>