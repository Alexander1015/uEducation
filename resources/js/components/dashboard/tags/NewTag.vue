<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-card class="mt-4 rounded mx-auto" elevation="3" max-width="1100">
                <v-row dense class="pl-1">
                    <v-col cols="4" class="bk_blue rounded-l d-none d-md-flex">
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
                            <v-card-title class="text-h5 mt-8">
                                <p class="mx-auto">NUEVA ETIQUETA</p>
                            </v-card-title>
                            <v-card-subtitle class="text-center">Cree una etiqueta nueva</v-card-subtitle>
                            <div class="px-2 pb-2">
                                <!-- Vista previa -->
                                <div class="mb-2">
                                    <small>Vista previa:</small>
                                    <v-chip label class="ml-2" :color="form.color_bk" :style='"color:" + form.color_txt + ";"'>
                                        <v-icon left>label</v-icon> {{ form.name }}
                                    </v-chip>
                                </div>
                                <!-- Formulario de ingreso -->
                                <v-form ref="form" lazy-validation>
                                    <small class="font-italic txt_red">Obligatorio *</small>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field v-model="form.name" :rules="nameRules" label="Título *"
                                                tabindex="1" required>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <p>Color de fondo:</p>
                                            <v-btn class="width_100 bk_brown txt_white"
                                                @click.prevent="form.color_bk = '#E0E0E0'">
                                                Reiniciar color
                                            </v-btn>
                                            <v-color-picker v-model="form.color_bk" class="mx-auto my-2"
                                                hide-mode-switch mode="hexa">
                                            </v-color-picker>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <p>Color del texto:</p>
                                            <v-btn class="width_100 bk_brown txt_white"
                                                @click.prevent="form.color_txt = '#676767'">
                                                Reiniciar color
                                            </v-btn>
                                            <v-color-picker v-model="form.color_txt" class="mx-auto my-2"
                                                hide-mode-switch mode="hexa">
                                            </v-color-picker>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </div>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn outlined @click.prevent="returnTags">
                                    <v-icon left>keyboard_double_arrow_left</v-icon>
                                    Regresar
                                </v-btn>
                                <v-btn class="txt_white bk_green" @click.prevent="registerTags">
                                    <v-icon left>save</v-icon>
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </div>
                    </v-col>
                </v-row>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "NewTags",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-new_tag.jpg",
            lazy: "/img/lazy/banner-new_tag.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            name: "",
            color_bk: "#E0E0E0",
            color_txt: "#676767",
        },
        nameRules: [
            v => !!v || 'El titulo es requerido',
            v => (v && v.length <= 250) || 'El titulo debe tener menos de 250 carácteres',
        ],
    }),
    methods: {
        returnTags() {
            this.$router.push({ name: "tags" });
        },
        async registerTags() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de crear la etiqueta?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('name', this.form.name);
                            data.append('background_color', this.form.color_bk);
                            data.append('text_color', this.form.color_txt);
                            this.axios.post('/api/tag', data)
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
                                            this.$router.push({ name: "tags" });
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
}
</script>