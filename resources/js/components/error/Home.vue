<template>
    <v-main class="ma-2">
        <v-card class="mt-6 pa-10 mx-auto bk_tags_bk" elevation="2" max-width="600">
            <p class="text-h2 text-center txt_red">
                Error 404
            </p>
            <v-divider></v-divider>
            <p class="text-h5 text-center my-4">
                Página no encontrada...
            </p>
            <v-img class="mx-auto" src="/img/logo/logo-w-letters.png"
                lazy-src="/img/lazy/logo-w-letters.png" height="200" contain>
                <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="grey lighten-5">
                        </v-progress-circular>
                    </v-row>
                </template>
            </v-img>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: 'Error',
}
</script>