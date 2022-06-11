<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-btn class="mr-4 mt-4 new_btn" text small @click.prevent="returnSubjects">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="text-h6 mt-11 mb-4 ml-2">
                <template v-if="subject.name">
                    <p>{{ subject.name.toUpperCase() }}</p>
                </template>
                <template v-else>
                    <v-icon>remove</v-icon>
                </template>
            </div>
            <v-card class="mt-4 mx-auto" elevation="0" max-width="1250">
                <v-tabs vertical>
                    <!-- Menú vertical -->
                    <v-tab>
                        <v-row>
                            <v-col cols="2" class="mx-auto">
                                <v-icon>
                                    book
                                </v-icon>
                            </v-col>
                            <v-col cols="10" class="mx-auto mt-1">
                                Información
                            </v-col>
                        </v-row>
                    </v-tab>
                    <v-tab>
                        <v-row>
                            <v-col cols="2" class="mx-auto">
                                <v-icon>
                                    local_library
                                </v-icon>
                            </v-col>
                            <v-col cols="10" class="mx-auto mt-1">
                                Otros
                            </v-col>
                        </v-row>
                    </v-tab>
                    <!-- Información del curso -->
                    <v-tab-item>
                        <v-card-subtitle class="text-center">
                            Información almacenada del curso seleccionado
                        </v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Formulario -->
                            <v-form ref="form_information" lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Titulo *" tabindex="1" required>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </div>
                        <v-card-actions>
                            <v-btn class="txt_white bk_green width_100 mt-2" @click.prevent="editSubject">
                                <v-icon left>save</v-icon>
                                Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <div class="px-4 py-2">
                                <div>
                                    <v-card-subtitle class="text-justify">
                                        Cambie el estado del curso en el sistema (Si esta desactivado no podra ser
                                        visualizado por parte del lector)
                                    </v-card-subtitle>
                                    <v-form ref="form_status" lazy-validation>
                                        <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                            label="Estado"></v-select>
                                    </v-form>
                                    <v-btn class="txt_white bk_green width_100" @click.prevent="statusSubject">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </div>
                                <v-divider class="mt-8 mb-4"></v-divider>
                                <div>
                                    <v-card-subtitle class="text-justify">
                                        Elimine el curso seleccionado de la base de datos, esta opcion no se puede
                                        revertir
                                    </v-card-subtitle>
                                    <v-btn class="txt_white bk_red width_100" @click.prevent="deleteSubject">
                                        <v-icon left>delete</v-icon>
                                        Eliminar curso
                                    </v-btn>
                                </div>
                            </div>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "EditSubject",
    data: () => ({
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        items_status: ["Activo", "Desactivado"],
        form_information: {
            name: "",
        },
        form_status: {
            status: "",
        },
        subject: {},
        info: {
            nameRules: [
                v => !!v || 'El titulo es requerido',
                v => (v && v.length <= 250) || 'El titulo debe tener menos de 250 carácteres',
            ],
        },
        name: "",
    }),
    mounted() {
        this.showSubject()
    },
    methods: {
        returnSubjects() {
            this.$router.push({ name: "subjects" });
        },
        async showSubject() {
            this.overlay = true;
            if (this.$route.params.id) {
                await this.axios.get('/api/subject/' + this.$route.params.id)
                    .then(response => {
                        this.subject = response.data;
                        if (!this.subject.name) {
                            this.overlay = false;
                            this.$router.push({ name: "subjects" });
                        }
                        else {
                            this.form_information.name = this.subject.name;
                            if (this.subject.status == 0) this.form_status.status = "Desactivado";
                            else if (this.subject.status == 1) this.form_status.status = "Activo";
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
            }
            else {
                this.overlay = false;
                this.$router.push({ name: "subjects" });
            }
        },
        async editSubject() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar la información del curso?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('name', this.form_information.name);
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
                                            this.showSubject()
                                        }
                                        this.overlay = false;
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
        async statusSubject() {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado del curso?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        let type = 3;
                        if (this.form_status.status == "Activo") type = 1;
                        else if (this.form_status.status == "Desactivado") type = 0;
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/subject/status/' + this.$route.params.id, data)
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
                                        this.showSubject();
                                    }
                                    this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.overlay = false;
                            });
                    }
                });
        },
        async deleteSubject() {
            await this.$swal({
                title: '¿Esta seguro de eliminar el curso?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/subject/' + this.$route.params.id)
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
                                        this.overlay = false;
                                        this.$router.push({ name: "subjects" });
                                    }
                                    else this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.sweet.title = "Error"
                                this.sweet.icon = "error";
                                this.$swal({
                                    title: this.sweet.title,
                                    icon: this.sweet.icon,
                                    text: error,
                                });
                                this.overlay = false;
                            });
                    }
                });
        },
    },
}
</script>