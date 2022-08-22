<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-2 mt-1 mb-4 mx-auto px-5 py-3">
            <div class="mb-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="records()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">REGISTROS</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_blue txt_white" v-bind="attrs" v-on="on" @click.stop="gotoNew()">
                            <v-icon>folder_copy</v-icon>
                        </v-btn>
                    </template>
                    <span>Generar la bitacora del ciclo</span>
                </v-tooltip>
            </div>
            <div class="mb-8">
                <p>Bitacora de registros de las suscripciones de docentes y estudiantes.</p>
            </div>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeRecords",
    data: () => ({
        overlay: false,
        user: {},
    }),
    mounted() {
        this.records();
    },
    methods: {
         gotoNew() {
            this.overlay = true;
            this.$router.push({ name: "newRecord" });
        },
        async records() {
            this.overlay = true;
            await this.axios.get('/api/auth')
                .then(response => {
                    this.user = response.data;
                }).catch((error) => {
                    console.log(error);
                    this.axios.post('/api/logout')
                        .then(response => {
                            window.location.href = "/auth"
                        }).catch((error) => {
                            console.log(error);
                        });
                });
            this.overlay = false;
        },
    },
}
</script>