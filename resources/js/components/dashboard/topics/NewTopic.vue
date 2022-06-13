<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-4 my-4">
            <v-card class="mt-4 mx-auto" elevation="0" max-width="1100">
                <v-row dense class="pl-1">
                    <v-col cols="3" class="bk_blue my-1 d-none d-md-flex">
                        <v-img class="img_login" :src='banner.img' :lazy-src='banner.lazy'>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5">
                                    </v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </v-col>
                    <v-col>
                        <div class="pb-4 mx-4">
                            <v-card-title class="text-h5 mt-4">
                                <p class="mx-auto">NUEVO TEMA</p>
                            </v-card-title>
                            <v-card-subtitle class="text-center">Cree un tema nuevo</v-card-subtitle>
                            <div class="px-2 pb-2">
                                <!-- Formulario -->
                                <v-form ref="form" lazy-validation>
                                    <small class="font-italic txt_red">Obligatorio *</small>
                                    <v-row dense>
                                        <v-col cols="12" sm="12" md="6">
                                            <v-autocomplete v-model="form.subject" :rules="subjectRules"
                                                :items="subjects" clearable clear-icon="cancel" label="Curso *"
                                                tabindex="1" required>
                                            </v-autocomplete>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <v-text-field v-model="form.name" :rules="nameRules" label="Titulo *"
                                                tabindex="2" required>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-textarea counter v-model="form.abstract" :rules="abstractRules" clearable
                                                clear-icon="cancel" rows="2" label="Descripción" tabindex="3">
                                            </v-textarea>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-file-input v-model="form.img" @change="preview_img"
                                                label="Haz clic(k) aquí para subir una imagen" id="img"
                                                prepend-icon="photo_camera" :rules="imgRules"
                                                accept="image/jpeg, image/jpg, image/png, image/gif, image/svg"
                                                show-size tabindex="4">
                                            </v-file-input>
                                            <template v-if="prev_img.url_img != '/img/topics/blank.png'">
                                                <v-btn class="bk_brown txt_white width_100" @click="clean_img">
                                                    Reiniciar imagen
                                                </v-btn>
                                            </template>
                                            <v-img class="mt-4 mx-auto" :src="prev_img.url_img"
                                                :lazy-src='prev_img.lazy_img' :max-height="prev_img.height"
                                                :max-width="prev_img.width" contain>
                                                <template v-slot:placeholder>
                                                    <v-row class="fill-height ma-0" align="center" justify="center">
                                                        <v-progress-circular indeterminate color="grey lighten-5">
                                                        </v-progress-circular>
                                                    </v-row>
                                                </template>
                                            </v-img>
                                        </v-col>
                                    </v-row>
                                </v-form>
                            </div>
                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-btn class="width_100" outlined @click.prevent="returnTopics">
                                        <v-icon left>keyboard_double_arrow_left</v-icon>
                                        Regresar
                                    </v-btn>
                                </v-col>
                                <v-col cols="12" sm="12" md="6">
                                    <v-btn class="txt_white bk_green width_100" @click.prevent="registerTopic">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </div>
                    </v-col>
                </v-row>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "NewTopic",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-new_topic.jpg",
            lazy: "/img/lazy/banner-new_topic.jpg",
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        form: {
            subject: "",
            name: "",
            abstract: "",
            img: null,
        },
        subjects: [],
        subjectRules: [
            v => !!v || 'El curso es requerido',
        ],
        nameRules: [
            v => !!v || 'El titulo es requerido',
            v => (v && v.length <= 250) || 'El titulo debe tener menos de 250 carácteres',
        ],
        abstractRules: [
            v => (!v || v.length <= 300) || 'La descripción debe tener menos de 300 carácteres',
        ],
        imgRules: [
            v => (!v || v.size <= 25000000) || 'La imagen debe ser menor a 25MB',
        ],
        prev_img: {
            url_img: "/img/topics/blank.png",
            lazy_img: "/img/lazy_topics/blank.png",
            height: 200,
            width: 300,
        }
    }),
    mounted() {
        this.showSubjects()
    },
    methods: {
        async showSubjects() {
            await this.axios.get('/api/getsubjects')
                .then(response => {
                    this.subjects = response.data;
                })
                .catch(error => {
                    this.subjects = []
                });
        },
        returnTopics() {
            this.$router.push({ name: "topics" });
        },
        async registerTopic() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de crear el tema?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('subject', this.form.subject);
                            data.append('name', this.form.name);
                            data.append('abstract', this.form.abstract);
                            this.form.img = document.querySelector('#img').files[0];
                            if (this.form.img) {
                                data.append('img', this.form.img);
                            }
                            this.axios.post('/api/topic', data)
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
                                            this.$router.push({ name: "editTopic", params: { id: response.data.topic } });
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
        preview_img() {
            this.prev_img.url_img = URL.createObjectURL(this.form.avatar);
            this.prev_img.lazy_img = URL.createObjectURL(this.form.avatar);
        },
        clean_img() {
            this.prev_img.url_img = "/img/topics/blank.png";
            this.prev_img.lazy_img = "/img/lazy_topics/blank.png";
            this.form.avatar = null;
        }
    },
}
</script>