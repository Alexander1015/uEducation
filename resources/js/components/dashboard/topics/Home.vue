<template>
    <v-main class="ma-2">
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Contenido -->
        <div class="mx-2 mt-1 mb-4 mx-auto px-5 py-3">
            <div class="mb-4">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="mt-n1" v-bind="attrs" v-on="on" icon @click.stop="allTopics()">
                            <v-icon>autorenew</v-icon>
                        </v-btn>
                    </template>
                    <span>Actualizar</span>
                </v-tooltip>
                <span class="text-h6">TEMAS</span>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn class="ml-4 mt-n2 bk_green txt_white" v-bind="attrs" v-on="on" @click.stop="gotoNew()">
                            <v-icon>add_box</v-icon>
                        </v-btn>
                    </template>
                    <span>Nuevo</span>
                </v-tooltip>
            </div>
            <div class="mb-8">
                <p>Listado de los temas existentes en la aplicación</p>
            </div>
            <v-row class="mb-1">
                <!-- Search -->
                <v-col cols="12" sm="6">
                    <v-text-field v-model="search" tabindex="1" prepend-icon="search" label="Búsqueda general" dense>
                    </v-text-field>
                </v-col>
                <!-- Subjects -->
                <v-col cols="12" sm="6">
                    <v-autocomplete v-model="subjects" :items="data_subject" clearable clear-icon="cancel"
                        label="Buscar por código del curso" tabindex="2" dense :loading="loading_table" item-text="code"
                        no-data-text="No se encuentra información para mostrar" prepend-icon="collections_bookmark"
                        append-icon="arrow_drop_down" hide-selected required>
                        <template v-slot:selection="data">
                            {{ data.item.code }} ({{ data.item.name }})
                        </template>
                        <template v-slot:item="data">
                            <v-list-item-content>
                                {{ data.item.code }} ({{ data.item.name }})
                            </v-list-item-content>
                        </template>
                    </v-autocomplete>
                    <!--  -->
                </v-col>
            </v-row>
            <!-- Tabla -->
            <v-data-table :headers="headers" :items="data" :items-per-page="10" :footer-props="{
                showFirstLastPage: true,
                firstIcon: 'first_page',
                lastIcon: 'last_page',
                prevIcon: 'chevron_left',
                nextIcon: 'chevron_right'
            }" :loading="loading_table" loading-text="Obteniendo información"
                no-data-text="No se ha obtenido información" no-results-text="No se obtuvieron resultados" multi-sort
                :search="search" fixed-header align="center">
                <template v-slot:item.img="{ item }">
                    <template v-if="item.img">
                        <v-img class="mx-auto" :src='"/img/topics/" + item.img + "/index.png"'
                            :lazy-src='"/img/topics/" + item.img + "/lazy.png"' max-height="40" max-width="60" contain>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5">
                                    </v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </template>
                    <template v-else>
                        <v-img class="mx-auto" src="/img/topics/blank.png" lazy-src="/img/topics/blank_lazy.png"
                            max-height="40" max-width="60" contain>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5">
                                    </v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </template>
                </template>
                <template v-slot:item.subject="{ item }">
                    <template v-if="item.subject">
                        <template v-if="item.subject_status == 0">
                            {{ item.subject }}<br />
                            <v-tooltip bottom color="error">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-icon class="hand txt_red" v-bind="attrs" v-on="on">
                                        warning
                                    </v-icon>
                                </template>
                                <span>
                                    Está materia esta deshabilitada. <br />
                                    Todo tema atribuido a este no podrá <br />
                                    ser accedido por el lector.
                                </span>
                            </v-tooltip>
                        </template>
                        <template v-else>
                            {{ item.subject }}
                        </template>
                    </template>
                    <template v-else>
                        <v-icon>remove</v-icon>
                    </template>
                </template>
                <template v-slot:item.user="{ item }">
                    <template v-if="item.user">
                        {{ item.user }}
                    </template>
                    <template v-else>
                        <v-icon>remove</v-icon>
                    </template>
                </template>
                <template v-slot:item.created_at="{ item }">
                    <template v-if="item.created_at">
                        {{ item.created_at }}
                    </template>
                    <template v-else>
                        <v-icon>remove</v-icon>
                    </template>
                </template>
                <template v-slot:item.user_update="{ item }">
                    <template v-if="item.user_update">
                        {{ item.user_update }}
                    </template>
                    <template v-else>
                        <v-icon>remove</v-icon>
                    </template>
                </template>
                <template v-slot:item.updated_at="{ item }">
                    <template v-if="item.updated_at">
                        {{ item.updated_at }}
                    </template>
                    <template v-else>
                        <v-icon>remove</v-icon>
                    </template>
                </template>
                <template v-slot:item.status="{ item }">
                    <div>
                        <template v-if="item.status == 0">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusTopic(item.slug, 1)">
                                        <v-icon>
                                            check_box_outline_blank
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Habilitar</span>
                            </v-tooltip>
                        </template>
                        <template v-else-if="item.status == 1">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn icon v-bind="attrs" v-on="on" @click.stop="statusTopic(item.slug, 0)">
                                        <v-icon>
                                            check_box
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Modo Borrador</span>
                            </v-tooltip>
                        </template>
                        <template v-else>
                            <v-icon>indeterminate_check_box</v-icon>
                        </template>
                    </div>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon class="txt_blue" v-bind="attrs" v-on="on" @click.stop="gotoEdit(item.slug)">
                                <v-icon>
                                    settings
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Ver tema</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon class="txt_red" @click.prevent="deleteTopic(item.slug)" v-bind="attrs"
                                v-on="on">
                                <v-icon>
                                    delete
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Eliminar</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeTopic",
    data: () => ({
        overlay: false,
        loading_table: true,
        search: '',
        subjects: '',
        data: [],
        data_subject: [],
    }),
    computed: {
        headers() {
            return [
                { text: 'Portada', value: 'img', align: 'center', sortable: false },
                { text: 'Título', value: 'name', align: 'center' },
                {
                    text: 'Curso', value: 'subject', align: 'center',
                    filter: value => {
                        return value.toString().toLowerCase().includes((this.subjects ? this.subjects : "").toLowerCase())
                    },
                },
                { text: 'Creado por', value: 'user', align: 'center' },
                { text: 'Creado el', value: 'created_at', align: 'center' },
                { text: 'Actualizado por', value: 'user_update', align: 'center' },
                { text: 'Actualizado el', value: 'updated_at', align: 'center' },
                { text: 'Estádo', value: 'status', align: 'center' },
                { text: 'Acciones', value: 'actions', align: 'center', sortable: false },
            ]
        }
    },
    mounted() {
        this.allTopics();
    },
    methods: {
        gotoEdit(item) {
            this.overlay = true;
            this.$router.push({ name: "editTopic", params: { slug: item } });
        },
        gotoNew() {
            this.overlay = true;
            this.$router.push({ name: "newTopic" });
        },
        async allTopics() {
            this.overlay = true;
            this.loading_table = true;
            this.data = [];
            await this.axios.get('/api/topic')
                .then(response => {
                    const item = response.data;
                    this.data = item.data;
                    this.data_subject = item.subjects;
                    this.loading_table = false;
                    this.overlay = false;
                })
                .catch(error => {
                    this.data = [];
                    this.loading_table = false;
                    this.overlay = false;
                })
        },
        async deleteTopic(item) {
            await this.$swal({
                title: '¿Está seguro de eliminar el tema?',
                text: "Está acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        this.axios.delete('/api/topic/' + item)
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
                                    this.allTopics()
                                    this.overlay = false;
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                    this.allTopics()
                                });
                            });
                    }
                });
        },
        async statusTopic(item, type) {
            await this.$swal({
                title: '¿Está seguro de ' + (type == 1 ? "habilitar" : (type == 0 ? "deshabilitar" : "cambiar el estado de")) + ' el tema seleccionado?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar',
            })
                .then(result => {
                    if (result.isConfirmed) {
                        this.overlay = true;
                        let data = new FormData();
                        data.append('status', type);
                        data.append('_method', "put");
                        this.axios.post('/api/topic/status/' + item, data)
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
                                    this.overlay = false;
                                    this.allTopics()
                                });
                            })
                            .catch(error => {
                                this.$swal({
                                    title: "Error",
                                    icon: "error",
                                    text: "Ha ocurrido un error en la aplicación",
                                }).then(() => {
                                    console.log(error);
                                    this.overlay = false;
                                    this.allTopics()
                                });
                            });
                    }
                });
        }
    },
}
</script>