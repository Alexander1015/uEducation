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
                        <v-btn class="mr-4" text small @click.stop="returnTags()">
                            <v-icon left>keyboard_double_arrow_left</v-icon>
                            Regresar
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">NUEVA ETIQUETA</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Cree una etiqueta nueva</v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Vista previa -->
                            <div class="mb-2">
                                <small>Vista previa:</small>
                                <v-chip class="ml-2" :color="form.color_bk" :style='"color:" + form.color_txt + ";"'>
                                    {{ form.name }}
                                </v-chip>
                            </div>
                            <!-- Formulario de ingreso -->
                            <v-form ref="form" @submit.prevent="registerTags()" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form.name" :rules="nameRules" label="Título *"
                                            tabindex="1" clearable clear-icon="cancel" dense prepend-icon="sell"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <div>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn icon v-bind="attrs" v-on="on" class="txt_blue mt-n1"
                                                        @click.prevent="form.color_bk = '#E0E0E0'">
                                                        <v-icon>restart_alt</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Restablecer color</span>
                                            </v-tooltip>
                                            <span>Color de fondo:</span>
                                        </div>
                                        <v-color-picker v-model="form.color_bk" class="mx-auto my-2" hide-mode-switch
                                            mode="hexa" :rules="colorbkRules">
                                        </v-color-picker>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <div>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-btn icon v-bind="attrs" v-on="on" class="txt_blue mt-n1"
                                                        @click.prevent="form.color_txt = '#676767'">
                                                        <v-icon>restart_alt</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Restablecer color</span>
                                            </v-tooltip>
                                            <span>Color del texto:</span>
                                        </div>
                                        <v-color-picker v-model="form.color_txt" class="mx-auto my-2" hide-mode-switch
                                            mode="hexa" :rules="colortxtRules">
                                        </v-color-picker>
                                    </v-col>
                                </v-row>
                                <template v-if="
                                    form.name != '' &&
                                    form.color_bk != '' &&
                                    form.color_txt != ''
                                ">
                                    <v-btn class="txt_white bk_green" block type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn block disabled>
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
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
    name: "NewTags",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/tag.jpg",
            lazy: "/img/banner/tag_lazy.jpg",
        },
        overlay: false,
        form: {
            name: "",
            color_bk: "#E0E0E0",
            color_txt: "#676767",
        },
        nameRules: [
            v => !!v || 'El titulo es requerido',
            v => (v && v.length <= 25) || 'El titulo debe tener menos de 25 carácteres',
        ],
        colorbkRules: [
            v => !!v || 'El color es requerido, o deje el valor por defecto',
        ],
        colortxtRules: [
            v => !!v || 'El color es requerido, o deje el valor por defecto',
        ],
    }),
    methods: {
        returnTags() {
            this.overlay = true;
            if (this.$route.params.backnew) this.$router.push({ name: "newTopic" });
            else if (this.$route.params.backedit) this.$router.push({ name: "editTopic", params: { slug: this.$route.params.backedit } });
            else this.$router.push({ name: "tags" });
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
                                            if (this.$route.params.backnew) this.$router.push({ name: "newTopic" });
                                            else if (this.$route.params.backedit) this.$router.push({ name: "editTopic", params: { slug: this.$route.params.backedit } });
                                            else this.$router.push({ name: "tags" });
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