<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mt-2">
            <v-btn class="ml-4" text small @click.stop="returnSubjects()">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <v-card class="mt-2 mx-auto" elevation="2" max-width="1100">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>
                        <template v-if="subject.name">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn class="mt-n1" v-bind="attrs" v-on="on" small icon @click.stop="showUser()">
                                        <v-icon>autorenew</v-icon>
                                    </v-btn>
                                </template>
                                <span>Actualizar</span>
                            </v-tooltip>
                            <span>
                                {{ subject.name.toUpperCase() }}
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
                    <template v-if="topics.length > 0">
                        <v-tab>
                            <v-icon left>
                                fact_check
                            </v-icon>
                            Orden de temas
                        </v-tab>
                    </template>
                    <v-tab>
                        <v-icon left>
                            people
                        </v-icon>
                        Docentes
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            badge
                        </v-icon>
                        Estudiantes
                    </v-tab>
                    <v-tab>
                        <v-icon left>
                            local_library
                        </v-icon>
                        Otros
                    </v-tab>
                    <!-- Información de la materia -->
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Información almacenada de la materia seleccionada
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_information" @submit.prevent="editSubject()" lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Titulo *" tabindex="1" clearable clear-icon="cancel" dense
                                            prepend-icon="collections_bookmark" required>
                                        </v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="6">
                                        <v-btn class="bk_brown txt_white mb-2" block @click.stop="handleFileImport()">
                                            <v-icon left>file_upload</v-icon>
                                            Subir imágen
                                        </v-btn>
                                        <v-file-input ref="uploader" v-model="form_information.img"
                                            @change="preview_img()" class="d-none"
                                            label="Haz clic(k) aquí para subir una portada" id="img"
                                            prepend-icon="photo_camera" :rules="info.imgRules"
                                            accept="image/jpeg, image/jpg, image/png, image/gif, image/svg" show-size>
                                        </v-file-input>
                                        <template v-if="prev_img.url_img != '/img/subjects/blank.png'">
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
                                    form_information.name != subject.name ||
                                    form_information.img != null ||
                                    form_information.img_new != 0
                                ">
                                    <v-btn class="txt_white bk_green mt-2" block type="submit">
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>

                                </template>
                                <template v-else>
                                    <v-btn class="mt-2" block disabled>
                                        <v-icon left>save</v-icon>
                                        Guardar
                                    </v-btn>
                                </template>
                            </v-form>
                        </div>
                    </v-tab-item>
                    <template v-if="topics.length > 0">
                        <v-tab-item>
                            <div class="px-4 py-4">
                                <v-card-subtitle class="text-center">
                                    Lista de los temas atribuidos a esta materia, ordenelos para la vista del lector.
                                </v-card-subtitle>
                                <draggable class="list-group" tag="ul" v-model="topics" v-bind="dragOptions"
                                    @start="drag = true" @end="drag = false">
                                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                                        <li id="topic_drag" class="cursor bk_brown txt_white my-1 mr-5 pa-2"
                                            v-for="item in topics" :key="item.key">
                                            {{ item.key }} - {{ item.name }}
                                            <v-icon class="topic_drag_icon txt_white">view_list</v-icon>
                                        </li>
                                    </transition-group>
                                </draggable>
                                <template v-if="topics != topics_copy">
                                    <v-btn class="txt_white bk_green mt-4" block @click.stop="saveListTopics()">
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
                            </div>
                        </v-tab-item>
                    </template>
                    <v-tab-item>
                        <v-tabs grow>
                            <!-- Menú grow -->
                            <v-tab>
                                <v-icon left>
                                    fact_check
                                </v-icon>
                                Lista actual
                            </v-tab>
                            <v-tab>
                                <v-icon left>
                                    group_add
                                </v-icon>
                                Suscribir docentes
                            </v-tab>
                            <v-tab-item>
                                <div class="px-4 py-4">
                                    <v-card-subtitle class="text-center">
                                        Listado de los docentes inscritos actualmente en la materia seleccionada
                                    </v-card-subtitle>
                                </div>
                            </v-tab-item>
                            <v-tab-item>
                                <div class="px-4 py-4">
                                    <v-card-subtitle class="text-center">
                                        Suscribir docentes a la materia seleccionada
                                    </v-card-subtitle>
                                    <v-row>
                                        <v-col cols="12" md="10">
                                            <v-text-field v-model="search_all_users" class="my-1" prepend-icon="search"
                                                label="Buscar" tabindex="1" dense>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" md="2">

                                            <template v-if="selected.length > 0">
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-btn class="bk_green txt_white width_100" v-bind="attrs"
                                                            v-on="on" @click.stop="suscribe()">
                                                            <v-icon left>playlist_add</v-icon>
                                                            Suscribir
                                                        </v-btn>
                                                    </template>
                                                    <span>Suscribir docentes seleccionados</span>
                                                </v-tooltip>
                                            </template>
                                            <template v-else>
                                                <v-btn class="width_100" v-bind="attrs" v-on="on"
                                                    @click.stop="suscribe()" disabled>
                                                    <v-icon left>playlist_add</v-icon>
                                                    Suscribir
                                                </v-btn>
                                            </template>
                                        </v-col>
                                    </v-row>
                                    <v-data-table class="mt-2" v-model="selected" :headers="headers_all_users"
                                        :items="all_users" :items-per-page="10" :single-select="singleSelect"
                                        item-key="id" show-select :footer-props="{
                                            showFirstLastPage: true,
                                            firstIcon: 'first_page',
                                            lastIcon: 'last_page',
                                            prevIcon: 'chevron_left',
                                            nextIcon: 'chevron_right'
                                        }" :loading="overlay" loading-text="Obteniendo información"
                                        no-data-text="No se ha obtenido información"
                                        no-results-text="No se obtuvieron resultados" multi-sort
                                        :search="search_all_users" align="center">
                                        <template v-slot:header.data-table-select="{ props, on }">
                                            <v-checkbox :input-value="props.value" :indeterminate="props.indeterminate"
                                                @change="on.input" off-icon="check_box_outline_blank"
                                                on-icon="check_box" indeterminate-icon="indeterminate_check_box"
                                                hide-details>
                                            </v-checkbox>
                                        </template>
                                        <template v-slot:item.data-table-select="{ isSelected, select }">
                                            <v-checkbox :input-value="isSelected" @click="select(!isSelected)"
                                                off-icon="check_box_outline_blank" on-icon="check_box"
                                                indeterminate-icon="indeterminate_check_box" hide-details>
                                            </v-checkbox>
                                        </template>
                                        <template v-slot:item.avatar="{ item }">
                                            <template v-if="item.avatar">
                                                <v-list-item-avatar class="mx-auto">
                                                    <v-img :src='"/img/users/" + item.avatar + "/index.png"'
                                                        max-height='38' max-width="38"
                                                        :lazy-src='"/img/users/" + item.avatar + "/lazy.png"'>
                                                        <template v-slot:placeholder>
                                                            <v-row class="fill-height ma-0" align="center"
                                                                justify="center">
                                                                <v-progress-circular indeterminate
                                                                    color="grey lighten-5">
                                                                </v-progress-circular>
                                                            </v-row>
                                                        </template>
                                                    </v-img>
                                                </v-list-item-avatar>
                                            </template>
                                            <template v-else>
                                                <v-list-item-avatar class="mx-auto">
                                                    <v-img src="/img/users/blank.png" max-height="38" max-width="38"
                                                        lazy-src="/img/users/blank_lazy.png">
                                                        <template v-slot:placeholder>
                                                            <v-row class="fill-height ma-0" align="center"
                                                                justify="center">
                                                                <v-progress-circular indeterminate
                                                                    color="grey lighten-5">
                                                                </v-progress-circular>
                                                            </v-row>
                                                        </template>
                                                    </v-img>
                                                </v-list-item-avatar>
                                            </template>
                                        </template>
                                    </v-data-table>
                                </div>
                            </v-tab-item>
                        </v-tabs>
                    </v-tab-item>
                    <v-tab-item>
                        <v-tabs grow>
                            <!-- Menú grow -->
                            <v-tab>
                                <v-icon left>
                                    fact_check
                                </v-icon>
                                Lista actual
                            </v-tab>
                            <v-tab>
                                <v-icon left>
                                    group_add
                                </v-icon>
                                Suscribir estudiantes
                            </v-tab>
                            <v-tab-item>
                                <div class="px-4 py-4">
                                    <v-card-subtitle class="text-center">
                                        Listado de los estudiantes inscritos actualmente en la materia seleccionada
                                    </v-card-subtitle>
                                </div>
                            </v-tab-item>
                            <v-tab-item>
                                <div class="px-4 py-4">
                                    <v-card-subtitle class="text-center">
                                        Suscribir estudiantes a la materia seleccionada
                                    </v-card-subtitle>
                                </div>
                            </v-tab-item>
                        </v-tabs>
                    </v-tab-item>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Cambie el estado de la materia en el sistema (Si esta deshabilitado no podra
                                    ser
                                    visualizado por parte del lector)
                                </v-card-subtitle>
                                <v-form ref="form_status" @submit.prevent="statusSubject" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estado" :rules="statusRules" dense prepend-icon="rule"></v-select>
                                    <template
                                        v-if="form_status.status != (subject.status == 1 ? 'Habilitado' : 'Deshabilitado')">
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
                            <template v-if="login_user.type == '0'">
                                <v-divider class="mt-8 mb-4"></v-divider>
                                <div>
                                    <v-card-subtitle class="text-justify">
                                        Elimine la materia seleccionada de la base de datos, esta opcion no se
                                        puede
                                        revertir
                                    </v-card-subtitle>
                                    <v-btn class="txt_white bk_red" block @click.stop="deleteSubject()">
                                        <v-icon left>delete</v-icon>
                                        Eliminar materia
                                    </v-btn>
                                </div>
                            </template>
                        </div>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </div>
    </v-main>
</template>

<script>
import draggable from 'vuedraggable'

export default {
    name: "EditSubject",
    components: {
        draggable
    },
    data: () => ({
        overlay: false,
        items_status: ["Habilitado", "Deshabilitado"],
        drag: false,
        form_information: {
            name: "",
            img: null,
            img_new: 0,
        },
        prev_img: {
            url_img: "/img/subjects/blank.png",
            lazy_img: "/img/subjects/blank_lazy.png",
            height: 200,
            width: 300,
        },
        topics: [],
        topics_copy: [],
        form_status: {
            status: "",
        },
        subject: {},
        info: {
            nameRules: [
                v => !!v || 'El titulo de la materia es requerido',
                v => (v && v.length <= 100) || 'El titulo de la materia debe tener menos de 100 carácteres',
            ],
            imgRules: [
                v => (!v || v.size <= 25000000) || 'La imágen debe ser menor a 25MB',
            ],
        },
        statusRules: [
            v => !!v || 'Debe elegir un item',
        ],
        name: "",
        login_user: {},
        singleSelect: false,
        all_users: {},
        headers_all_users: [
            { text: 'Avatar', value: 'avatar', align: 'center', sortable: false },
            { text: 'Nombres', value: 'firstname', align: 'center' },
            { text: 'Apellidos', value: 'lastname', align: 'center' },
            { text: 'Usuarios', value: 'user', align: 'center' },
        ],
        selected: [],
        search_all_users: '',
    }),
    mounted() {
        this.showSubject();
    },
    computed: {
        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            };
        },
        address() {
            return this.$route.params.slug;
        }
    },
    watch: {
        address() {
            this.showSubject();
        }
    },
    methods: {
        async suscribe() {
            if (this.selected.length > 0) {
                await this.$swal({
                    title: '¿Esta seguro de suscribir los docentes seleccionados?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            let data = new FormData();
                            for (let user of this.selected) {
                                data.append('users[]', user.slug);
                            }
                            data.append('_method', "put");
                            this.axios.post('/api/getusr/' + this.address, data)
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
                                            this.showSubject();
                                        }
                                        this.overlay = false;
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
        handleFileImport() {
            this.$refs.uploader.$refs.input.click()
        },
        returnSubjects() {
            this.overlay = true;
            this.$router.push({ name: "subjects" });
        },
        async showSubject() {
            this.overlay = true;
            if (this.address) {
                // Obtenemos el usuario ingresado
                await this.axios.get('/api/auth')
                    .then(response => {
                        this.login_user = response.data;
                    }).catch((error) => {
                        console.log(error);
                        this.axios.post('/api/logout')
                            .then(response => {
                                window.location.href = "/auth"
                            }).catch((error) => {
                                console.log(error);
                                this.$router.push({ name: "error" });
                            });
                    });
                // Obtenemos todos los docentes
                await this.axios.get('/api/getusr')
                    .then(response => {
                        this.all_users = response.data;
                    }).catch((error) => {
                        console.log(error);
                    });
                // Obtenemos los datos del subject
                await this.axios.get('/api/subject/' + this.address)
                    .then(response => {
                        const item = response.data;
                        if (!item.subject.name) {
                            this.$router.push({ name: "error" });
                        }
                        else {
                            // Subject
                            this.subject = item.subject;
                            this.form_information.name = this.subject.name;
                            if (this.subject.img) {
                                this.prev_img.url_img = "/img/subjects/" + this.subject.img + "/index.png";
                                this.prev_img.lazy_img = "/img/subjects/" + this.subject.img + "/lazy.png";
                            }
                            this.form_information.img = null;
                            this.form_information.img_new = 0;
                            if (this.subject.status == 0) this.form_status.status = "Deshabilitado";
                            else if (this.subject.status == 1) this.form_status.status = "Habilitado";
                            // Topics
                            this.topics = this.topics_copy = item.topics;
                            this.overlay = false
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.$router.push({ name: "error" });
                    });
            }
            else {
                this.$router.push({ name: "error" });
            }
        },
        async editSubject() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar la información de la materia?',
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
                            if (this.form_information.img) {
                                data.append('img', this.form_information.img);
                            }
                            data.append('img_new', this.form_information.img_new);
                            data.append('_method', "put");
                            this.axios.post('/api/subject/' + this.address, data)
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
                                                this.$router.push({ name: "editSubject", params: { slug: response.data.reload } });
                                            }
                                            else {
                                                this.showSubject();
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
        async saveListTopics() {
            await this.$swal({
                title: '¿Esta seguro de cambiar lel orden de los temas seleccionados?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        for (let item of this.topics) {
                            data.append('topics[]', item.id);
                        }
                        data.append('_method', "put");
                        this.axios.post('/api/subject/gettopics/' + this.address, data)
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
                                        this.showSubject();
                                    }
                                    this.overlay = false;
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
                            });
                    }
                });
        },
        async statusSubject() {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado de la materia?',
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
                        this.axios.post('/api/subject/status/' + this.address, data)
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
                                        this.showSubject();
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
        async deleteSubject() {
            if (this.login_user.type == '0') {
                await this.$swal({
                    title: '¿Esta seguro de eliminar la materia?',
                    text: "Esta acción no se puede revertir",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                })
                    .then(result => {
                        if (result.isConfirmed) {
                            this.overlay = true;
                            this.axios.delete('/api/subject/' + this.address)
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
                                            this.$router.push({ name: "subjects" });
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
            }
        },
        preview_img() {
            this.form_information.img_new = 1;
            this.prev_img.url_img = URL.createObjectURL(this.form_information.img);
            this.prev_img.lazy_img = URL.createObjectURL(this.form_information.img);
        },
        clean_img() {
            this.prev_img.url_img = "/img/subjects/blank.png";
            this.prev_img.lazy_img = "/img/subjects/blank_lazy.png";
            this.form_information.img = null;
            this.form_information.img_new = 1;
        }
    },
}
</script>