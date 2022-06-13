<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-btn class="mr-4 mt-4 new_btn" text small @click.prevent="returnTopics">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="text-h6 mt-11 mb-4 ml-2">
                <template v-if="topic.name">
                    <p>{{ topic.name.toUpperCase() }}</p>
                </template>
                <template v-else>
                    <v-icon>remove</v-icon>
                </template>
            </div>
            <v-card class="mt-4 mx-auto" elevation="3" max-width="1100">
                <v-tabs grow>
                    <!-- Menú grow -->
                    <v-tab>
                        <v-icon left>
                            book
                        </v-icon>
                        Información
                    </v-tab>
                    <v-tab>
                        <v-icon>
                            local_library
                        </v-icon>
                        Otros
                    </v-tab>
                    <!-- Información del tema -->
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Información almacenada del tema seleccionado
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_information" lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Titulo *" tabindex="1" required>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                            </v-form>
                            <v-btn class="txt_white bk_green width_100 mt-2" @click.prevent="editTopic">
                                <v-icon left>save</v-icon>
                                Guardar
                            </v-btn>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Cambie el estado del tema en el sistema (Si esta desactivado no podra ser
                                    visualizado por parte del lector)
                                </v-card-subtitle>
                                <v-form ref="form_status" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estado" :rules="statusRules"></v-select>
                                </v-form>
                                <v-btn class="txt_white bk_green width_100" @click.prevent="statusTopic">
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
                                <v-btn class="txt_white bk_red width_100" @click.prevent="deleteTopic">
                                    <v-icon left>delete</v-icon>
                                    Eliminar curso
                                </v-btn>
                            </div>
                        </div>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "EditTopic",
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
        topic: {},
        info: {
            nameRules: [
                v => !!v || 'El titulo es requerido',
                v => (v && v.length <= 250) || 'El titulo debe tener menos de 250 carácteres',
            ],
        },
        statusRules: [
            v => !!v || 'Debe elegir un item',
        ],
        name: "",
    }),
    mounted() {
        this.showTopic()
    },
    methods: {
        returnTopics() {
            this.$router.push({ name: "topics" });
        },
        async showTopic() {
            this.overlay = true;
            if (this.$route.params.id) {
                await this.axios.get('/api/topic/' + this.$route.params.id)
                    .then(response => {
                        this.topic = response.data;
                        if (!this.topic.name) {
                            this.overlay = false;
                            this.$router.push({ name: "topics" });
                        }
                        else {
                            this.form_information.name = this.topic.name;
                            if (this.topic.status == 0) this.form_status.status = "Desactivado";
                            else if (this.topic.status == 1) this.form_status.status = "Activo";
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
            }
            else {
                this.overlay = false;
                this.$router.push({ name: "topics" });
            }
        },
        async editTopic() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar la información del tema?',
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
                            this.axios.post('/api/topics/' + this.$route.params.id, data)
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
                                            this.showTopic()
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
        async statusTopic() {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado del tema?',
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
                        this.axios.post('/api/topic/status/' + this.$route.params.id, data)
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
                                        this.showTopic();
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
        async deleteTopic() {
            await this.$swal({
                title: '¿Esta seguro de eliminar el tema?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/topic/' + this.$route.params.id)
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
                                        this.$router.push({ name: "topics" });
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