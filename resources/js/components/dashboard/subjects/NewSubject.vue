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
                        <v-btn class="mr-4" text small @click.stop="returnSubjects()">
                            <v-icon left>keyboard_double_arrow_left</v-icon>
                            Regresar
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">NUEVO MATERIA</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Cree una materia nueva</v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Formulario -->
                            <v-form ref="form" @submit.prevent="registerSubject()" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form.name" :rules="nameRules" label="Titulo *"
                                            tabindex="1" dense prepend-icon="collections_bookmark" clearable
                                            clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-btn class="bk_brown txt_white width_100 mb-2"
                                            @click.stop="handleFileImport()">
                                            <v-icon left>file_upload</v-icon>
                                            Subir imagen
                                        </v-btn>
                                        <v-file-input ref="uploader" v-model="form.img" @change="preview_img()"
                                            label="Haz clic(k) aquí para subir una portada" id="img" class="d-none"
                                            prepend-icon="photo_camera" :rules="imgRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size>
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/subjects/blank.png'">
                                            <v-btn class="bk_brown txt_white width_100" @click.stop="clean_img()">
                                                <v-icon left>delete</v-icon>
                                                Borrar imagen
                                            </v-btn>
                                        </template>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-img class="mt-0 mx-auto" :src="prev_img.url_img"
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
                                <template v-if="form.name != ''">
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
                    </div>
                </v-col>
            </v-row>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "NewSubject",
    data: () => ({
        dialog: true,
        banner: {
            img: "/img/banner/banner-new_subject.jpg",
            lazy: "/img/lazy/banner-new_subject.jpg",
        },
        overlay: false,
        form: {
            name: "",
            img: null,
        },
        nameRules: [
            v => !!v || 'El titulo de la materia es requerido',
            v => (v && v.length <= 100) || 'El titulo de la materia debe tener menos de 100 carácteres',
        ],
        imgRules: [
            v => (!v || v.size <= 25000000) || 'La imagen debe ser menor a 25MB',
        ],
        prev_img: {
            url_img: "/img/subjects/blank.png",
            lazy_img: "/img/lazy_subjects/blank.png",
            height: 200,
            width: 300,
        }
    }),
    methods: {
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        returnSubjects() {
            this.overlay = true;
            if (this.$route.params.backnew) this.$router.push({ name: "newTopic" });
            else if (this.$route.params.backedit) this.$router.push({ name: "editTopic", params: { slug: this.$route.params.backedit } });
            else this.$router.push({ name: "subjects" });
        },
        async registerSubject() {
            if (this.$refs.form.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de crear la materia?',
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
                            this.form.img = document.querySelector('#img').files[0];
                            if (this.form.img) {
                                data.append('img', this.form.img);
                            }
                            this.axios.post('/api/subject', data)
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
                                            else this.$router.push({ name: "subjects" });
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
        preview_img() {
            this.prev_img.url_img = URL.createObjectURL(this.form.img);
            this.prev_img.lazy_img = URL.createObjectURL(this.form.img);
        },
        clean_img() {
            this.prev_img.url_img = "/img/subjects/blank.png";
            this.prev_img.lazy_img = "/img/lazy_subjects/blank.png";
            this.form.img = null;
        }
    },
}
</script>