<template>
    <v-main>
        <v-dialog v-model="dialog" max-width="900" persistent transition="dialog-bottom-transition">
            <!-- Overlay -->
            <v-overlay :value="overlay" :absolute="true">
                <v-progress-circular indeterminate size="64"></v-progress-circular>
            </v-overlay>
            <!-- Contenido -->
            <v-card class="pa-0 ma-0">
                <v-container>
                    <v-row>
                        <v-col cols="4" class="bk_blue d-none d-md-flex pa-0">
                            <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                                <template v-slot:placeholder>
                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                    </v-row>
                                </template>
                            </v-img>
                        </v-col>
                        <v-col>
                            <v-card-title class="text-h5">
                                <p class="mx-auto">Modificar informacion de {{ name }}</p>
                            </v-card-title>
                            <v-card-subtitle class="text-center">Modifique el curso seleccionado</v-card-subtitle>
                            <div class="px-2 pb-2">
                                <!-- Formulario de ingreso -->
                                <v-form ref="form" enctype="multipart/form-data" lazy-validation>
                                    <small class="font-italic txt_red">Obligatorio *</small>
                                    <v-text-field v-model="form.name" :rules="nameRules" label="Titulo *" tabindex="1"
                                        required>
                                    </v-text-field>
                                </v-form>
                            </div>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn class="bk_red txt_white" :to='{ name: "subjects" }'>
                                    Cancelar
                                </v-btn>
                                <v-btn class="txt_white bk_green" @click="editSubject">
                                    Modificar</v-btn>
                            </v-card-actions>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </v-main>
</template>

<script>
export default {
    name: "editSubject",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-update_user.jpg",
            lazy: "/img/lazy/banner-update_user.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            name: "",
        },
        nameRules: [
            v => !!v || 'El titulo es requerido',
            v => (v && v.length <= 250) || 'El titulo debe tener menos de 250 carácteres',
        ],
        name: "",
    }),
    methods: {
        async showSubject() {
            this.overlay = true;
            if (this.$route.params.id) {
                await this.axios.get('/api/subject/' + this.$route.params.id)
                    .then(response => {
                        const { name } = response.data;
                        if (!name) {
                            this.overlay = false;
                            window.location.href = "/dashboard/subjects"
                        }
                        else {
                            this.name = name;
                            this.form.name = name;
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
            }
            else {
                this.overlay = false;
                window.location.href = "/dashboard/subjects"
            }
        },
        async editSubject() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar el curso?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            //Mostramos los datos asi por la imagen
                            let data = new FormData();
                            data.append('name', this.form.name);
                            data.append('_method', "put");
                            this.axios.post('/api/subject/' + this.$route.params.id, data)
                                .then(response => {
                                    if (response.data.complete) {
                                        this.sweet.title = "Éxito"
                                        this.sweet.icon = "success";
                                    }
                                    else {
                                        this.sweet.title = "Error"
                                        this.sweet.icon = "error";
                                    }
                                    this.$swal({
                                        title: this.sweet.title,
                                        icon: this.sweet.icon,
                                        text: response.data.message,
                                    }).then(() => {
                                        if (response.data.complete) {
                                            window.location.href = "/dashboard/subjects";
                                            this.overlay = false;
                                        }
                                        else this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                    this.$swal({
                                        title: this.sweet.title,
                                        icon: this.sweet.icon,
                                        text: error,
                                    });
                                    this.overlay = false;
                                })
                        }
                    });
            }
            else {
                this.overlay = false;
            }
        },
    },
    mounted() {
        this.showSubject()
    },
}
</script>