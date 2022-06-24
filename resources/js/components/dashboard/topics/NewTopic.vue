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
                    <v-img class="img_login rounded-sm" :src='banner.img' :lazy-src='banner.lazy'>
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
                        <v-btn class="mr-4" text small @click.prevent="returnTopics">
                            <v-icon left>keyboard_double_arrow_left</v-icon>
                            Regresar
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">NUEVO TEMA</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Cree un tema nuevo</v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Formulario -->
                            <v-form ref="form" @submit.prevent="registerTopic" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form.name" :rules="nameRules" label="Titulo *"
                                            tabindex="1" dense prepend-icon="library_books" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="form.subject" :rules="subjectRules"
                                            :items="data_subject" clearable clear-icon="cancel" label="Curso *"
                                            tabindex="2" dense :loading="loading_subjects"
                                            no-data-text="No se encuentra información para mostrar"
                                            prepend-icon="collections_bookmark" append-icon="arrow_drop_down"
                                            hide-selected required>
                                        </v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="form.tags" :rules="tagsRules" :items="data_tags"
                                            clearable clear-icon="cancel" label="Etiquetas (Max. 5)*" tabindex="3" dense
                                            :loading="loading_tags" item-text="name"
                                            no-data-text="No se encuentra información para mostrar"
                                            prepend-icon="local_offer" append-icon="arrow_drop_down" chips small-chips
                                            multiple @change="limitTags" hide-selected required>
                                            <template v-slot:selection="data">
                                                <v-chip label class="my-1" :color="data.item.background_color"
                                                    :style='"color:" + data.item.text_color + ";"' v-bind="data.attrs"
                                                    close @click="data.select" @click:close="remove(data.item)"
                                                    :input-value="data.selected" close-icon="close">
                                                    <v-icon left>label</v-icon> {{ data.item.name }}
                                                </v-chip>
                                            </template>
                                            <template v-slot:item="data">
                                                <v-list-item-content v-text="data.item.name"></v-list-item-content>
                                            </template>
                                        </v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea counter v-model="form.abstract" :rules="abstractRules" clearable
                                            clear-icon="cancel" rows="2" label="Descripción" dense
                                            prepend-icon="subject" tabindex="4">
                                        </v-textarea>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-file-input v-model="form.img" @change="preview_img"
                                            label="Haz clic(k) aquí para subir una portada" id="img"
                                            prepend-icon="photo_camera" :rules="imgRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size
                                            tabindex="5">
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/topics/blank.png'">
                                            <v-btn class="bk_brown txt_white width_100" @click="clean_img">
                                                Borrar imagen
                                            </v-btn>
                                        </template>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
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
                                <template v-if="
                                    form.name != '' &&
                                    form.subject != '' &&
                                    form.tags.length > 0
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
                    </div>
                </v-col>
            </v-row>
        </v-card>
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
            tags: [],
            abstract: "",
            img: null,
        },
        loading_subjects: true,
        loading_tags: true,
        data_subject: [],
        data_tags: [],
        subjectRules: [
            v => !!v || 'El curso es requerido',
        ],
        tagsRules: [
            v => !!(v && v.length) || 'Debe elegir al menos una etiqueta',
        ],
        nameRules: [
            v => !!v || 'El titulo es requerido',
            v => (v && v.length <= 100) || 'El titulo debe tener menos de 100 carácteres',
        ],
        abstractRules: [
            v => (!v || v.length <= 250) || 'La descripción debe tener menos de 250 carácteres',
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
        this.showSubjects();
        this.showTags();
    },
    methods: {
        limitTags() {
            if (this.form.tags.length > 5) this.form.tags.pop();
        },
        remove(item) {
            const index = this.form.tags.indexOf(item.name)
            if (index >= 0) this.form.tags.splice(index, 1)
        },
        async showSubjects() {
            this.loading_subjects = true;
            await this.axios.get('/api/getsubjects')
                .then(response => {
                    this.data_subject = response.data;
                    this.loading_subjects = false;
                })
                .catch(error => {
                    this.data_subject = [];
                    this.loading_subjects = false;
                });
        },
        async showTags() {
            this.loading_tags = true;
            await this.axios.get('/api/gettags')
                .then(response => {
                    this.data_tags = response.data;
                    this.loading_tags = false;
                })
                .catch(error => {
                    this.data_subject = [];
                    this.loading_tags = false;
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
                            if (this.form.tags.length > 0) {
                                for (let tag of this.form.tags) {
                                    data.append('tags[]', tag);
                                }
                            }
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
            this.prev_img.url_img = URL.createObjectURL(this.form.img);
            this.prev_img.lazy_img = URL.createObjectURL(this.form.img);
        },
        clean_img() {
            this.prev_img.url_img = "/img/topics/blank.png";
            this.prev_img.lazy_img = "/img/lazy_topics/blank.png";
            this.form.img = null;
        }
    },
}
</script>