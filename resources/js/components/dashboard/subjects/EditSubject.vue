<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mt-2">
            <v-btn class="ml-4" text small @click.prevent="returnSubjects">
                <v-icon left>keyboard_double_arrow_left</v-icon>
                Regresar
            </v-btn>
            <div class="new_btn mr-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" fab small @click.prevent="showSubject()" elevation="3"
                            class="bk_blue txt_white mr-4">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Recargar</span>
                </v-tooltip>
            </div>
            <v-card class="mt-4 mx-auto" elevation="2" max-width="700">
                <v-toolbar flat class="bk_blue" dark>
                    <v-toolbar-title>
                        <template v-if="subject.name">
                            {{ subject.name.toUpperCase() }}
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
                            local_library
                        </v-icon>
                        Otros
                    </v-tab>
                    <!-- Información del curso -->
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <v-card-subtitle class="text-center">
                                Información almacenada del curso seleccionado
                            </v-card-subtitle>
                            <!-- Formulario -->
                            <v-form ref="form_information" @submit.prevent="editSubject" lazy-validation>
                                <small class="font-italic txt_red mb-2">Obligatorio *</small>
                                <v-row class="mt-2">
                                    <v-col cols="12">
                                        <v-text-field v-model="form_information.name" :rules="info.nameRules"
                                            label="Titulo *" tabindex="1" dense prepend-icon="collections_bookmark"
                                            required>
                                        </v-text-field>
                                    </v-col>
                                </v-row>
                                <template v-if="form_information.name != subject.name">
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
                    <template v-if="topics.length > 0">
                        <v-tab-item>
                            <div class="px-4 py-4">
                                <v-card-subtitle class="text-center">
                                    Lista de los temas atribuidos a esta materia, ordenelos para su vista por parte de
                                    los lectores.
                                </v-card-subtitle>
                                <draggable class="list-group" tag="ul" v-model="topics" v-bind="dragOptions"
                                    @start="drag = true" @end="drag = false">
                                    <transition-group type="transition" :name="!drag ? 'flip-list' : null">
                                        <li id="topic_drag" class="bk_brown txt_white my-1 mr-5 pa-2"
                                            v-for="item in topics" :key="item.key">
                                            {{ item.key }} - {{ item.name }}
                                            <v-icon class="topic_drag_icon txt_white">view_list</v-icon>
                                        </li>
                                    </transition-group>
                                </draggable>
                                <template v-if="topics != topics_copy">
                                    <v-btn class="txt_white bk_green width_100 mt-2" @click.prevent="saveListTopics">
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
                    </template>
                    <v-tab-item>
                        <div class="px-4 py-4">
                            <div>
                                <v-card-subtitle class="text-justify">
                                    Cambie el estado del curso en el sistema (Si esta desactivado no podra ser
                                    visualizado por parte del lector)
                                </v-card-subtitle>
                                <v-form ref="form_status" @submit.prevent="statusSubject" lazy-validation>
                                    <v-select class="width_100" v-model="form_status.status" :items="items_status"
                                        label="Estado" :rules="statusRules" dense prepend-icon="rule"></v-select>
                                    <template
                                        v-if="form_status.status != (subject.status == 1 ? 'Activo' : 'Desactivado')">
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
                                <v-btn class="txt_white bk_red width_100" @click.prevent="deleteSubject">
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
import draggable from 'vuedraggable'

export default {
    name: "EditSubject",
    components: {
        draggable
    },
    data: () => ({
        overlay: false,
        sweet: {
            icon: "error",
            title: "Error",
        },
        items_status: ["Activo", "Desactivado"],
        drag: false,
        form_information: {
            name: "",
        },
        topics: {},
        topics_copy: {},
        form_status: {
            status: "",
        },
        subject: {},
        info: {
            nameRules: [
                v => !!v || 'El titulo del curso es requerido',
                v => (v && v.length <= 100) || 'El titulo del curso debe tener menos de 100 carácteres',
            ],
        },
        statusRules: [
            v => !!v || 'Debe elegir un item',
        ],
        name: "",
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
        }
    },
    methods: {
        returnSubjects() {
            this.$router.push({ name: "subjects" });
        },
        async showSubject() {
            this.overlay = true;
            if (this.$route.params.slug) {
                await this.axios.get('/api/subject/' + this.$route.params.slug)
                    .then(response => {
                        this.subject = response.data;
                        if (!this.subject.name) {
                            this.overlay = false;
                            this.$router.push({ name: "subjects" });
                        }
                        else {
                            this.form_information.name = this.subject.name;
                            if (this.subject.status == 0) this.form_status.status = "Desactivado";
                            else if (this.subject.status == 1) this.form_status.status = "Activo";
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
                this.overlay = true;
                await this.axios.get('/api/subject/gettopics/' + this.$route.params.slug)
                    .then(response => {
                        this.topics = this.topics_copy = response.data;
                        this.overlay = false;
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                    });
            }
            else {
                this.overlay = false;
                this.$router.push({ name: "subjects" });
            }
        },
        async editSubject() {
            if (this.$refs.form_information.validate()) {
                await this.$swal({
                    title: '¿Esta seguro de modificar la información del curso?',
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
                            data.append('_method', "put");
                            this.axios.post('/api/subject/' + this.$route.params.slug, data)
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
                                                this.$router.push({ name: "editSubject", params: { slug: response.data.reload } });
                                            }
                                            else this.showSubject();
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
        async saveListTopics() {
            if (this.$refs.form_information.validate()) {
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
                            this.axios.post('/api/subject/gettopics/' + this.$route.params.slug, data)
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
                                            this.showSubject();
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
        async statusSubject() {
            await this.$swal({
                title: '¿Esta seguro de cambiar el estado del curso?',
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
                        this.axios.post('/api/subject/status/' + this.$route.params.slug, data)
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
                                        this.getTopics();
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
        async deleteSubject() {
            await this.$swal({
                title: '¿Esta seguro de eliminar el curso?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/subject/' + this.$route.params.slug)
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
                                        this.$router.push({ name: "subjects" });
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