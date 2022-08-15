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
                        <v-btn class="mr-4" text small @click.stop="returnTopics()">
                            <v-icon left>keyboard_double_arrow_left</v-icon>
                            Regresar
                        </v-btn>
                        <v-card-title class="text-h5">
                            <p class="mx-auto">NUEVO TEMA</p>
                        </v-card-title>
                        <v-card-subtitle class="text-center">Cree un tema nuevo</v-card-subtitle>
                        <div class="px-2 pb-2">
                            <!-- Formulario -->
                            <v-form ref="form" @submit.prevent="registerTopic()" lazy-validation>
                                <small class="font-italic txt_red">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form.name" :rules="nameRules" label="Titulo *"
                                            tabindex="1" dense prepend-icon="library_books" clearable
                                            clear-icon="cancel" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-row>
                                            <v-col>
                                                <v-autocomplete v-model="form.subject" :rules="subjectRules"
                                                    :items="data_subject" clearable clear-icon="cancel" label="Curso *"
                                                    tabindex="3" dense :loading="loading_subjects"
                                                    no-data-text="No se encuentra información para mostrar"
                                                    prepend-icon="collections_bookmark" append-icon="arrow_drop_down"
                                                    hide-selected required>
                                                    <template v-slot:selection="data">
                                                        <template v-if="data.item.status == 0">
                                                            <v-tooltip bottom color="error">
                                                                <template v-slot:activator="{ on, attrs }">
                                                                    <v-icon class="mr-2 hand txt_red" v-bind="attrs"
                                                                        v-on="on">
                                                                        warning
                                                                    </v-icon>
                                                                </template>
                                                                <span>
                                                                    Esta materia esta deshabilitada. <br />
                                                                    Aunque se pueda seleccionar, todo tema <br />
                                                                    atribuido a este no podra ser accedido <br />
                                                                    por el lector.
                                                                </span>
                                                            </v-tooltip>
                                                            {{ data.item.name }}
                                                        </template>
                                                        <template v-else>
                                                            {{ data.item.name }}
                                                        </template>
                                                    </template>
                                                    <template v-slot:item="data">
                                                        <v-list-item-content>
                                                            <template v-if="data.item.status == 0">
                                                                <span>
                                                                    <v-tooltip bottom color="error">
                                                                        <template v-slot:activator="{ on, attrs }">
                                                                            <v-icon class="mr-2 hand txt_red"
                                                                                v-bind="attrs" v-on="on">
                                                                                warning
                                                                            </v-icon>
                                                                        </template>
                                                                        <span>
                                                                            Esta materia esta deshabilitada. <br />
                                                                            Aunque se pueda seleccionar, todo tema
                                                                            <br />
                                                                            atribuido a este no podra ser accedido
                                                                            <br />
                                                                            por el lector.
                                                                        </span>
                                                                    </v-tooltip>
                                                                    {{ data.item.name }}
                                                                </span>
                                                            </template>
                                                            <template v-else>
                                                                {{ data.item.name }}
                                                            </template>
                                                        </v-list-item-content>
                                                    </template>
                                                </v-autocomplete>
                                            </v-col>
                                            <v-col cols="1">
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-btn v-bind="attrs" v-on="on" icon
                                                            @click.stop="gotoSubject()">
                                                            <v-icon>post_add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Agregar cursos</span>
                                                </v-tooltip>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-row>
                                            <v-col>
                                                <v-autocomplete v-model="form.tags" :rules="tagsRules"
                                                    :items="data_tags" clearable clear-icon="cancel"
                                                    label="Etiquetas (Max. 5)*" tabindex="4" dense
                                                    :loading="loading_tags" item-text="name"
                                                    no-data-text="No se encuentra información para mostrar"
                                                    prepend-icon="local_offer" append-icon="arrow_drop_down" chips
                                                    small-chips multiple @change="limitTags()" hide-selected required>
                                                    <template v-slot:selection="data">
                                                        <v-chip class="my-1" :color="data.item.background_color"
                                                            :style='"color:" + data.item.text_color + ";"'
                                                            v-bind="data.attrs" close @click="data.select"
                                                            @click:close="remove(data.item)"
                                                            :input-value="data.selected" close-icon="close">
                                                            <template v-if="data.item.status == 0">
                                                                <v-tooltip bottom color="error">
                                                                    <template v-slot:activator="{ on, attrs }">
                                                                        <v-icon class="mr-2 hand" v-bind="attrs"
                                                                            v-on="on">
                                                                            warning
                                                                        </v-icon>
                                                                    </template>
                                                                    <span>
                                                                        Esta etiqueta esta deshabilitada. <br />
                                                                        Aunque se pueda seleccionar, el lector no <br />
                                                                        podra verla.
                                                                    </span>
                                                                </v-tooltip>
                                                                {{ data.item.name }}
                                                            </template>
                                                            <template v-else>
                                                                {{ data.item.name }}
                                                            </template>
                                                        </v-chip>
                                                    </template>
                                                    <template v-slot:item="data">
                                                        <v-list-item-content>
                                                            <template v-if="data.item.status == 0">
                                                                <span>
                                                                    <v-tooltip bottom color="error">
                                                                        <template v-slot:activator="{ on, attrs }">
                                                                            <v-icon class="mr-2 hand txt_red" v-bind="attrs"
                                                                                v-on="on">
                                                                                warning
                                                                            </v-icon>
                                                                        </template>
                                                                        <span>
                                                                            Esta etiqueta esta deshabilitada. <br />
                                                                            Aunque se pueda seleccionar, el lector no
                                                                            <br />
                                                                            podra verla.
                                                                        </span>
                                                                    </v-tooltip>
                                                                    {{ data.item.name }}
                                                                </span>
                                                            </template>
                                                            <template v-else>
                                                                {{ data.item.name }}
                                                            </template>
                                                        </v-list-item-content>
                                                    </template>
                                                </v-autocomplete>
                                            </v-col>
                                            <v-col cols="1">
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-btn v-bind="attrs" v-on="on" icon @click.stop="gotoTag()">
                                                            <v-icon>bookmark_add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Agregar etiquetas</span>
                                                </v-tooltip>
                                            </v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-textarea counter v-model="form.abstract" :rules="abstractRules" clearable
                                            clear-icon="cancel" rows="2" label="Descripción" dense
                                            prepend-icon="subject" tabindex="5">
                                        </v-textarea>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-btn class="bk_brown txt_white mb-2" block @click.stop="handleFileImport()">
                                            <v-icon left>file_upload</v-icon>
                                            Subir imágen
                                        </v-btn>
                                        <v-file-input ref="uploader" v-model="form.img" @change="preview_img()"
                                            label="Haz clic(k) aquí para subir una portada" id="img" class="d-none"
                                            prepend-icon="photo_camera" :rules="imgRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size>
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/topics/blank.png'">
                                            <v-btn class="bk_brown txt_white" block @click.stop="clean_img()">
                                                <v-icon left>delete</v-icon>
                                                Borrar imágen
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
                                <template v-if="
                                    form.name != '' &&
                                    form.subject != '' &&
                                    form.tags.length > 0
                                ">
                                    <v-btn class="txt_white bk_green mt-4" block type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn class="mt-4" block disabled>
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
            img: "/img/banner/topic.jpg",
            lazy: "/img/banner/topic_lazy.jpg",
        },
        overlay: false,
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
            v => (!v || v.size <= 25000000) || 'La imágen debe ser menor a 25MB',
        ],
        prev_img: {
            url_img: "/img/topics/blank.png",
            lazy_img: "/img/topics/blank_lazy.png",
            height: 200,
            width: 300,
        }
    }),
    mounted() {
        this.showData();
    },
    methods: {
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        returnTopics() {
            this.overlay = true;
            this.$router.push({ name: "topics" });
        },
        gotoSubject() {
            this.overlay = true;
            this.$router.push({ name: "newSubject", params: { backnew: true } });
        },
        gotoTag() {
            this.overlay = true;
            this.$router.push({ name: "newTag", params: { backnew: true } });
        },
        limitTags() {
            if (this.form.tags.length > 5) this.form.tags.pop();
        },
        remove(item) {
            const index = this.form.tags.indexOf(item.name)
            if (index >= 0) this.form.tags.splice(index, 1)
        },
        async showData() {
            this.overlay = true;
            await this.axios.get('/api/getts')
                .then(response => {
                    const items = response.data;
                    if (items.subjects) {
                        this.data_subject = items.subjects;
                    }
                    else this.data_subject = [];
                    if (items.tags) {
                        this.data_tags = items.tags;
                    }
                    else this.data_tags = [];
                    this.overlay = false;
                    this.loading_tags = false;
                    this.loading_subjects = false;
                })
                .catch(error => {
                    this.data_subject = [];
                    this.data_tags = [];
                    this.overlay = false;
                    this.loading_tags = false;
                    this.loading_subjects = false;
                });
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
                            data.append('subject', this.form.subject.name);
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
                                            this.$router.push({ name: "editTopic", params: { slug: response.data.topic } });
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
            this.prev_img.url_img = "/img/topics/blank.png";
            this.prev_img.lazy_img = "/img/topics/blank_lazy.png";
            this.form.img = null;
        }
    },
}
</script>