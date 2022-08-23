<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <v-card class="mt-2 rounded mx-auto" elevation="2" max-width="1100">
            <v-row dense class="pl-1">
                <v-col cols="3" class="bk_blue rounded-l d-none d-md-flex">
                    <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </v-col>
                <v-col>
                    <div class="pb-4 mx-4">
                        <v-btn class="mr-4" text small @click.stop="returnRecords()">
                            <v-icon left>keyboard_double_arrow_left</v-icon>
                            Regresar
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">GENERAR REGISTROS</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">
                            Genere la bitacora del ciclo actual según su especificación<br />
                            <small class="text-center font-italic txt_red">
                                Advertencia: Al generar la bitacora se eliminarán todas las suscripciones actuales de
                                los estudiantes; pero la de los docentes seguirá activa. Además este proceso puede durar
                                varios minutos dependiendo de la información
                                almacenada
                            </small>
                        </v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Formulario -->
                            <v-form ref="form" @submit.prevent="registerRecord()" lazy-validation>
                                <v-row class="my-2">
                                    <v-col cols="12" sm="6">
                                        <v-autocomplete v-model="form.period" :rules="periodRules" :items="data_period"
                                            clearable clear-icon="cancel" label="Ciclo de estudio" tabindex="1" dense
                                            no-data-text="No se encuentra información para mostrar" prepend-icon="today"
                                            append-icon="arrow_drop_down" hide-selected required>
                                        </v-autocomplete>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-autocomplete v-model="form.year" :rules="yearRules" :items="data_year"
                                            clearable clear-icon="cancel" label="Año" tabindex="2" dense
                                            no-data-text="No se encuentra información para mostrar" prepend-icon="event"
                                            append-icon="arrow_drop_down" hide-selected required>
                                        </v-autocomplete>
                                    </v-col>
                                </v-row>
                                <v-btn class="txt_white bk_green mt-2" block type="submit">
                                    <v-icon left>drive_file_move</v-icon>
                                    Generar bitacora
                                </v-btn>
                            </v-form>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "NewRecord",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/register.jpg",
            lazy: "/img/banner/register_lazy.jpg",
        },
        form: {
            period: "",
            year: new Date().getFullYear(),
        },
        data_period: [
            "01",
            "02",
            "03"
        ],
        data_year: [
            new Date().getFullYear(),
            new Date().getFullYear() - 1,
            new Date().getFullYear() - 2,
            new Date().getFullYear() - 3,
            new Date().getFullYear() - 4,
            new Date().getFullYear() - 5,
        ],
        periodRules: [
            v => !!v || 'El ciclo requerido',
        ],
        yearRules: [
            v => !!v || 'El año es requerido',
        ],
        overlay: false,
    }),
    methods: {
        returnRecords() {
            this.overlay = true;
            this.$router.push({ name: "records" });
        },
        async registerRecord() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de crear la bitacora del ciclo actual especificado, esta acción es irreversible?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('period', this.form.period);
                            data.append('year', this.form.year);
                            this.axios.post('/api/record', data)
                                .then(response => {
                                    let title = "Error";
                                    let icon = "error";
                                    if (response.data.complete) {
                                        title = "Éxito"
                                        icon = "success";
                                    }
                                    this.$swal({
                                        title: title,
                                        icon: icon,
                                        text: response.data.message,
                                    }).then(() => {
                                        if (response.data.complete) {
                                            this.$router.push({ name: "records" });
                                        }
                                        else this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.$swal({
                                        title: "Error",
                                        icon: "error",
                                        text: "Ha ocurrido un error en la aplicación",
                                    }).then(() => {
                                        console.log(error);
                                        this.overlay = false;
                                    });
                                });
                        }
                    });
            }
            else {
                this.overlay = false;
            }
        }
    },
}
</script>