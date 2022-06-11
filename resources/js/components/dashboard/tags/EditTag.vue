<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-btn class="mr-4 mt-4 new_btn" text small @click.prevent="returnTags">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="text-h6 mt-11 mb-4 ml-2">
                <template v-if="tag.name">
                    <p>{{ tag.name.toUpperCase() }}</p>
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
                        <v-card-title class="text-h5">
                            <p class="mx-auto">Modificar informacion de {{ name }}</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Modifique la etiqueta seleccionada</v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Vista previa -->
                            <div class="mb-2">
                                <small>Vista previa:</small>
                                <v-chip label class="ml-2" :color="form_information.color_bk"
                                    :style='"color:" + form_information.color_txt + ";"'>
                                    <v-icon left>label</v-icon> {{ form_information.name }}
                                </v-chip>
                            </div>
                            <!-- Formulario de ingreso -->
                            <v-form ref="form_information" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Título *" tabindex="1" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <p>Color de fondo:</p>
                                        <v-btn class="width_100 bk_brown txt_white"
                                            @click.prevent="form_information.color_bk = '#E0E0E0'">
                                            Reiniciar color
                                        </v-btn>
                                        <v-color-picker v-model="form_information.color_bk" class="mx-auto my-2"
                                            hide-mode-switch mode="hexa">
                                        </v-color-picker>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <p>Color del texto:</p>
                                        <v-btn class="width_100 bk_brown txt_white"
                                            @click.prevent="form_information.color_txt = '#676767'">
                                            Reiniciar color
                                        </v-btn>
                                        <v-color-picker v-model="form_information.color_txt" class="mx-auto my-2"
                                            hide-mode-switch mode="hexa">
                                        </v-color-picker>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </div>
                        <v-card-actions>
                            <v-btn class="txt_white bk_green width_100 mt-2" @click.prevent="editTags">
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
                                        Cambie el estado de la etiqueta en el sistema (Si esta desactivado los temas que
                                        tengan la etiqueta no podran ser visualizados)
                                    </v-card-subtitle>
                                    <v-form ref="form_status" lazy-validation>
                                        <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                            label="Estado"></v-select>
                                    </v-form>
                                    <v-btn class="txt_white bk_green width_100" @click.prevent="statusTag">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </div>
                                <v-divider class="mt-8 mb-4"></v-divider>
                                <div>
                                    <v-card-subtitle class="text-justify">
                                        Elimine la etiqueta seleccionada de la base de datos, esta opcion no se puede
                                        revertir
                                    </v-card-subtitle>
                                    <v-btn class="txt_white bk_red width_100" @click.prevent="deleteTag">
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
    name: "EditTags",
    data: () => ({
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        items_status: ["Activo", "Desactivado"],
        form_information: {
            name: "",
            color_bk: "#E0E0E0",
            color_txt: "#676767",
        },
        form_status: {
            status: "",
        },
        tag: {},
        info: {
            nameRules: [
                v => !!v || 'El titulo es requerido',
                v => (v && v.length <= 250) || 'El titulo debe tener menos de 250 carácteres',
            ],
        },
        name: "",
    }),
    mounted() {
        this.showTag()
    },
    methods: {
        returnTags() {
            this.$router.push({ name: "tags" });
        },
        async showTag() {
            this.overlay = true;
            if (this.$route.params.id) {
                await this.axios.get('/api/tag/' + this.$route.params.id)
                    .then(response => {
                        this.tag = response.data;
                        if (!this.tag.name) {
                            this.overlay = false;
                            this.$router.push({ name: "tags" });
                        }
                        else {
                            this.form_information.name = this.tag.name;
                            this.form_information.color_bk = this.tag.background_color;
                            this.form_information.color_txt = this.tag.text_color;
                            if (this.tag.status == 0) this.form_status.status = "Desactivado";
                            else if (this.tag.status == 1) this.form_status.status = "Activo";
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
            }
            else {
                this.overlay = false;
                this.$router.push({ name: "tags" });
            }
        },
        async editTags() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar la etiqueta?',
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
                            data.append('name', this.form_information.name);
                            data.append('background_color', this.form_information.color_bk);
                            data.append('text_color', this.form_information.color_txt);
                            data.append('_method', "put");
                            this.axios.post('/api/tag/' + this.$route.params.id, data)
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
                                            this.showTag()
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
        async statusTag() {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado de la etiqueta?',
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
                        thshowTagis.axios.post('/api/tag/status/' + this.$route.params.id, data)
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
                                        this.showTag();
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
        async deleteTag() {
            await this.$swal({
                title: '¿Esta seguro de eliminar la etiqueta?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/tag/' + this.$route.params.id)
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
                                        this.$router.push({ name: "tags" });
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