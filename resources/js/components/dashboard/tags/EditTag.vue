<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mt-2">
            <v-btn class="ml-4" text small @click.stop="returnTags()">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <v-card class="mt-2 mx-auto" elevation="2" max-width="1100">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>
                        <template v-if="tag.name">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn class="mt-n1" v-bind="attrs" v-on="on" small icon @click.stop="showTag()">
                                        <v-icon>autorenew</v-icon>
                                    </v-btn>
                                </template>
                                <span>Actualizar</span>
                            </v-tooltip>
                            <span>
                                {{ tag.name.toUpperCase() }}
                            </span>
                        </template>
                        <template v-else>
                            <v-icon>remove</v-icon>
                        </template>
                    </v-toolbar-title>
                </v-toolbar>
                <v-tabs grow>
                    <!-- Menú grow -->
                    <v-tab>
                        <v-icon left>
                            book
                        </v-icon>
                        Información
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            local_library
                        </v-icon>
                        Otros
                    </v-tab>
                    <!-- Información del curso -->
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Información almacenada de la etiqueta seleccionada
                            </v-card-subtitle>
                            <!-- Vista previa -->
                            <div class="mb-2">
                                <small>Vista previa:</small>
                                <v-chip label class="ml-2" :color="form_information.color_bk"
                                    :style='"color:" + form_information.color_txt + ";"'>
                                    <v-icon left>label</v-icon> {{ form_information.name }}
                                </v-chip>
                            </div>
                            <!-- Formulario de ingreso -->
                            <v-form ref="form_information" @submit.prevent="editTags()" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Título *" tabindex="1" clearable clear-icon="cancel" dense
                                            prepend-icon="sell" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <div>
                                            <span>Color de fondo:</span>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }"
                                                    v-if="form_information.color_bk != '#E0E0E0'">
                                                    <v-btn icon v-bind="attrs" v-on="on" class="txt_blue mt-n1"
                                                        @click.prevent="form_information.color_bk = '#E0E0E0'">
                                                        <v-icon>restart_alt</v-icon>
                                                    </v-btn>
                                                </template>
                                                <template v-slot:activator="{ on, attrs }" v-else>
                                                    <v-btn icon v-bind="attrs" v-on="on" class="txt_blue mt-n1"
                                                        @click.prevent="form_information.color_bk = tag.background_color">
                                                        <v-icon>restart_alt</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Restablecer color</span>
                                            </v-tooltip>
                                        </div>
                                        <v-color-picker v-model="form_information.color_bk" class="mx-auto my-2"
                                            hide-mode-switch mode="hexa" :rules="info.colorbkRules">
                                        </v-color-picker>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <div>
                                            <span>Color del texto:</span>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }"
                                                    v-if="form_information.color_txt != '#676767'">
                                                    <v-btn icon v-bind="attrs" v-on="on" class="txt_blue mt-n1"
                                                        @click.prevent="form_information.color_txt = '#676767'">
                                                        <v-icon>restart_alt</v-icon>
                                                    </v-btn>
                                                </template>
                                                <template v-slot:activator="{ on, attrs }" v-else>
                                                    <v-btn icon v-bind="attrs" v-on="on" class="txt_blue mt-n1"
                                                        @click.prevent="form_information.color_txt = tag.text_color">
                                                        <v-icon>restart_alt</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Restablecer color</span>
                                            </v-tooltip>
                                        </div>
                                        <v-color-picker v-model="form_information.color_txt" class="mx-auto my-2"
                                            hide-mode-switch mode="hexa" :rules="info.colortxtRules">
                                        </v-color-picker>
                                    </v-col>
                                </v-row>
                                <template v-if="
                                    form_information.name != tag.name ||
                                    form_information.color_bk != tag.background_color ||
                                    form_information.color_txt != tag.text_color
                                ">
                                    <v-btn class="txt_white bk_green width_100 mt-2" type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn class="width_100 mt-2" disabled>
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                            </v-form>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Cambie el estado de la etiqueta en el sistema (Si esta deshabilitado los temas que
                                    tengan la etiqueta no podran ser visualizados)
                                </v-card-subtitle>
                                <v-form ref="form_status" @submit.prevent="statusTag" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estado" :rules="statusRules" dense prepend-icon="rule"></v-select>
                                    <template v-if="form_status.status != (tag.status == 1 ? 'Habilitado' : 'Deshabilitado')">
                                        <v-btn class="txt_white bk_green width_100" type="submit">
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                    <template v-else>
                                        <v-btn class="width_100" disabled>
                                            <v-icon left>save</v-icon>
                                            Guardar
                                        </v-btn>
                                    </template>
                                </v-form>
                            </div>
                            <v-divider class="mt-8 mb-4"></v-divider>
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Elimine la etiqueta seleccionada de la base de datos, esta opcion no se puede
                                    revertir
                                </v-card-subtitle>
                                <v-btn class="txt_white bk_red width_100" @click.prevent="deleteTag">
                                    <v-icon left>delete</v-icon>
                                    Eliminar etiqueta
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
    name: "EditTags",
    data: () => ({
        overlay: false,
        items_status: ["Habilitado", "Deshabilitado"],
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
                v => (v && v.length <= 25) || 'El titulo de la etiqueta debe tener menos de 25 carácteres',
            ],
            colorbkRules: [
                v => !!v || 'El color es requerido, ó deje el valor por defecto',
            ],
            colortxtRules: [
                v => !!v || 'El color es requerido, ó deje el valor por defecto',
            ],
        },
        statusRules: [
            v => !!v || 'Debe elegir un item',
        ],
        name: "",
    }),
    mounted() {
        this.showTag()
    },
    computed: {
        address() {
            return this.$route.params.slug;
        }
    },
    watch: {
        address() {
            this.showTag();
        }
    },
    methods: {
        returnTags() {
            this.$router.push({ name: "tags" });
        },
        async showTag() {
            this.overlay = true;
            if (this.address) {
                await this.axios.get('/api/tag/' + this.address)
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
                            if (this.tag.status == 0) this.form_status.status = "Deshabilitado";
                            else if (this.tag.status == 1) this.form_status.status = "Habilitado";
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
                            this.axios.post('/api/tag/' + this.address, data)
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
                                            if (response.data.reload) {
                                                this.$router.push({ name: "editTag", params: { slug: response.data.reload } });
                                            }
                                            else {
                                                this.showTag();
                                                this.overlay = false;
                                            }
                                        }
                                        else this.overlay = false;
                                    });
                                }).catch(error => {
                                    this.$swal({
                                        title: "Error",
                                        icon: "error",
                                        text: "Ha ocurrido un error en la aplicación",
                                    }).then(() => {
                                        this.overlay = false;
                                        console.log(error);
                                    });
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
                        if (this.form_status.status == "Habilitado") type = 1;
                        else if (this.form_status.status == "Deshabilitado") type = 0;
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/tag/status/' + this.address, data)
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
                                        this.showTag();
                                    }
                                    this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    this.overlay = false;
                                    console.log(error);
                                });
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
                        this.axios.delete('/api/tag/' + this.address)
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
                                        this.$router.push({ name: "tags" });
                                    }
                                    else this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    this.overlay = false;
                                    console.log(error);
                                });
                            });
                    }
                });
        },
    },
}
</script>