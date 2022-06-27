<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mt-2">
            <v-btn class="ml-4" text small @click.prevent="returnTopics">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="new_btn mr-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" fab small @click.prevent="showTags(), showSubjects(), showTopic()" elevation="3"
                            class="bk_blue txt_white mr-4">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Recargar</span>
                </v-tooltip>
            </div>
            <v-card class="mt-4 mx-auto" elevation="2" max-width="1250">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>
                        <template v-if="topic.name">
                            {{ topic.name.toUpperCase() }}
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
                            menu_book
                        </v-icon>
                        Información
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            library_books
                        </v-icon>
                        Contenido
                    </v-tab>
                    <v-tab>
                        <v-icon left>
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
                            <v-form ref="form_information" @submit.prevent="editTopic" lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Titulo *" tabindex="1" dense prepend-icon="library_books" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.slug" label="Slug (Vista previa)" tabindex="2" dense
                                            prepend-icon="code_off" :loading="loading_slug" disabled>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="form_information.subject" :rules="info.subjectRules"
                                            :items="data_subject" clearable clear-icon="cancel" label="Curso *"
                                            tabindex="3" dense :loading="loading_subjects"
                                            no-data-text="No se encuentra información para mostrar"
                                            prepend-icon="collections_bookmark" append-icon="arrow_drop_down"
                                            hide-selected required>
                                        </v-autocomplete>
                                    </v-col>
                                    <v-col cols="12">
                                        <v-autocomplete v-model="form_information.tags" :rules="info.tagsRules"
                                            :items="data_tags" clearable clear-icon="cancel" label="Etiquetas (Max. 5)*"
                                            tabindex="4" dense :loading="loading_tags" item-text="name"
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
                                        <v-textarea v-model="form_information.abstract" counter
                                            :rules="info.abstractRules" clearable clear-icon="cancel" rows="2"
                                            label="Descripción" dense prepend-icon="subject" tabindex="5">
                                        </v-textarea>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-file-input v-model="form_information.img" @change="preview_img"
                                            label="Haz clic(k) aquí para subir una portada" id="img"
                                            prepend-icon="photo_camera" :rules="info.imgRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size
                                            tabindex="6">
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
                                    form_information.name != topic.name ||
                                    form_information.subject != form_information.copy_subject ||
                                    form_information.tags.length != form_information.tags_size ||
                                    form_information.tags != form_information.tags_copy ||
                                    form_information.abstract != topic.abstract ||
                                    form_information.img != null ||
                                    form_information.img_new != 0
                                ">
                                    <v-btn class="txt_white bk_green width_100 mt-4" type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                                <template v-else>
                                    <v-btn class="width_100 mt-4" disabled>
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                            </v-form>
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-title class="text-h5 mt-2">
                                <p class="mx-auto">CONTENIDO</p>
                            </v-card-title>
                            <div class="px-2 pb-2">
                                <ckeditor id="ckeditor" :editor="editor" v-model="editorData" :config="editorConfig"
                                    @ready="onReady">
                                </ckeditor>
                            </div>
                            <template v-if="editorData != topic.body">
                                <v-btn class="txt_white bk_green width_100 mt-2" @click.prevent="saveBody()">
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
                        </div>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Cambie el estado del tema en el sistema (Si esta en borrador el tema no podra ser
                                    visualizado por el lector)
                                </v-card-subtitle>
                                <v-form ref="form_status" @submit.prevent="statusTopic" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estado" :rules="statusRules" dense prepend-icon="rule"></v-select>
                                    <template v-if="form_status.status != (topic.status == 1 ? 'Activo' : 'Borrador')">
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
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
import '@ckeditor/ckeditor5-build-classic/build/translations/es';

export default {
    name: "EditTopic",
    data: () => ({
        editor: DecoupledEditor,
        editorData: "",
        editorConfig: {
            extraPlugins: [myCustomUploadAdapterPlugin],
            language: 'es',
            placeholder: 'Escribe aqui el contenido...',
        },
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        items_status: ["Activo", "Borrador"],
        form_information: {
            subject: "",
            copy_subject: "",
            tags: [],
            tags_copy: [],
            tags_size: 0,
            name: "",
            slug: "",
            abstract: "",
            img: null,
            img_new: 0,
        },
        form_status: {
            status: "",
        },
        loading_subjects: true,
        loading_tags: true,
        loading_slug: false,
        data_subject: [],
        data_tags: [],
        prev_img: {
            url_img: "/img/topics/blank.png",
            lazy_img: "/img/lazy_topics/blank.png",
            height: 200,
            width: 300,
        },
        topic: {},
        info: {
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
        },
        statusRules: [
            v => !!v || 'Debe elegir un item',
        ],
        name: "",
    }),
    watch: {
        "form_information.name"() {
            this.loading_slug = true;
            let data = new FormData();
            data.append('name', this.form_information.name);
            this.axios.post('/api/slug', data)
                .then(response => {
                    this.form_information.slug = response.data.slug;
                    this.loading_slug = false;
                }).catch(error => {
                    this.form_information.slug = "n/a";
                    this.loading_slug = false;
                })
        },
    },
    mounted() {
        this.showTags();
        this.showSubjects();
        this.showTopic();
    },
    methods: {
        limitTags() {
            if (this.form_information.tags.length > 5) this.form_information.tags.pop();
        },
        remove(item) {
            const index = this.form_information.tags.indexOf(item.name)
            if (index >= 0) this.form_information.tags.splice(index, 1)
        },
        onReady(editor) {
            // Insert the toolbar before the editable area.
            editor.ui.getEditableElement().parentElement.insertBefore(
                editor.ui.view.toolbar.element,
                editor.ui.getEditableElement()
            );
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
        async showSubjects() {
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
        returnTopics() {
            this.$router.push({ name: "topics" });
        },
        async showTopic() {
            this.overlay = true;
            if (this.$route.params.slug) {
                await this.axios.get('/api/topic/' + this.$route.params.slug)
                    .then(response => {
                        this.topic = response.data;
                        if (!this.topic.name) {
                            this.overlay = false;
                            this.$router.push({ name: "topics" });
                        }
                        else {
                            this.form_information.name = this.topic.name;
                            this.form_information.abstract = this.topic.abstract;
                            if (this.topic.img) {
                                this.prev_img.url_img = "/img/topics/" + this.topic.img;
                                this.prev_img.lazy_img = "/img/lazy_topics/" + this.topic.img;
                            }
                            this.form_information.img = null;
                            this.form_information.img_new = 0;
                            this.editorData = this.topic.body;
                            if (this.topic.status == 0) this.form_status.status = "Borrador";
                            else if (this.topic.status == 1) this.form_status.status = "Activo";
                            this.axios.get('/api/getsubjects/' + this.topic.subject_id)
                                .then(response_sub => {
                                    this.form_information.subject = response_sub.data.name;
                                    this.form_information.copy_subject = response_sub.data.name;
                                    this.overlay = false;
                                }).catch((error) => {
                                    console.log(error);
                                    this.form_information.subject = "";
                                    this.form_information.copy_subject = "";
                                    this.overlay = false;
                                });
                            this.axios.get('/api/gettags/' + this.topic.id)
                                .then(response_sub => {
                                    this.form_information.tags = response_sub.data;
                                    this.form_information.tags_copy = response_sub.data;
                                    this.form_information.tags_size = response_sub.data.length;
                                    this.overlay = false;
                                }).catch((error) => {
                                    console.log(error);
                                    this.form_information.tags = [];
                                    this.form_information.tags_copy = [];
                                    this.form_information.tags_size = 0;
                                    this.overlay = false;
                                });
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
                    title: '¿Esta seguro de modificar el tema?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            data.append('subject', this.form_information.subject);
                            if (this.form_information.tags.length > 0) {
                                for (let tag of this.form_information.tags) {
                                    data.append('tags[]', tag);
                                }
                            }
                            data.append('name', this.form_information.name);
                            data.append('abstract', this.form_information.abstract);
                            this.form_information.img = document.querySelector('#img').files[0];
                            if (this.form_information.img) {
                                data.append('img', this.form_information.img);
                            }
                            data.append('img_new', this.form_information.img_new);
                            data.append('_method', "put");
                            this.axios.post('/api/topic/' + this.$route.params.slug, data)
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
                                            if (response.data.reload) {
                                                this.$router.push({ name: "editTopic", params: { slug: response.data.reload } });
                                            }
                                            else this.showTopic();
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
        async saveBody() {
            await this.$swal({
                title: '¿Esta seguro de guardar el contenido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        data.append('body', this.editorData);
                        data.append('_method', "put");
                        this.axios.post('/api/topic/body/' + this.$route.params.slug, data)
                            .then(response => {
                                if (response.data.complete) {
                                    this.sweet.title = "Éxito"
                                    this.sweet.icon = "success";
                                }
                                else {
                                    this.sweet.title = "Error"
                                    this.sweet.icon = "error";
                                }
                                console.log(response.data.message);
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
                        else if (this.form_status.status == "Borrador") type = 0;
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/topic/status/' + this.$route.params.slug, data)
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
                        this.axios.delete('/api/topic/' + this.$route.params.slug)
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
        preview_img() {
            this.form_information.img_new = 1;
            this.prev_img.url_img = URL.createObjectURL(this.form_information.img);
            this.prev_img.lazy_img = URL.createObjectURL(this.form_information.img);
        },
        clean_img() {
            this.prev_img.url_img = "/img/topics/blank.png";
            this.prev_img.lazy_img = "/img/lazy_topics/blank.png";
            this.form_information.img = null;
            this.form_information.img_new = 1;
        }
    },
}

function myCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
    };
}

export class UploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(uploadedFile => {
                return new Promise((resolve, reject) => {
                    let data = new FormData();
                    data.append('image', uploadedFile);
                    data.append('_method', "put");
                    let url = window.location.href;
                    const slug = url.substring(url.lastIndexOf("/") + 1);
                    axios.post('/api/topic/upload/' + slug, data, {
                        headers: {
                            'Content-Type': 'multipart/form-data;'
                        },
                    })
                        .then(response => {
                            if (response.data.url) {
                                alert(response.data.message);
                                resolve({
                                    default: response.data.url
                                });
                            }
                            else {
                                reject(response.data.message);
                            }
                        }).catch(response => {
                            reject("Ha ocurrido un error al subir la imagen");
                        });
                });
            });
    }

    abort() {

    }
}

</script>